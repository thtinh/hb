<?php
/*
# ------------------------------------------------------------------------
# JA Rasite - Stable - Version 1.0 - Licence Owner JA115884
# ------------------------------------------------------------------------
# Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
# ------------------------------------------------------------------------
*/

// Check to ensure this file is included in Joomla!

defined( '_JEXEC' ) or die();
jimport( 'joomla.plugin.plugin' );
jimport('joomla.application.module.helper');

/**
 * Jatabs Content Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.5
 */


class plgContentJA_tabs extends JPlugin
{
  	var $style_default = '';
  
	function plgContentJA_tabs( &$subject, $params=null )
	{
		if (!$subject) return;
		parent::__construct( $subject, $params );
	}

	/**
	 * tabs prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 */
	function onPrepareContent( &$article, &$params, $limitstart )
	{
		global $mainframe;
		
		$plugin	=& JPluginHelper::getPlugin('content', 'ja_tabs');
		$pluginParams	= new JParameter( $plugin->params );

		if (JString::strpos( $article->text, '{jatabs' ) === false){
			$HSmethodDIRECT = false;
		}else{
			$HSmethodDIRECT = true;
		}
		
		if(!defined("JAMOOTAB_PLUGIN_HEADTAG")) $this->Jatabs_PrepareSettings($pluginParams);
		
		/*@var globol style */	  		
		if (!$this->style_default) {  
	      $this->style_default = $pluginParams->get('style') ? $pluginParams->get('style'):'default';
		}
				
		if($HSmethodDIRECT){
			require_once('plugins/content/ja_tabs/parser.php');
			$parser = new ReplaceCallbackParser('jatabs');
			$article->text =  $parser->parse ($article->text, array(&$this, 'Jatabs_replacer_DIRECT'));			
		}
		return $article->text;
	}

	function Jatabs_PrepareSettings($pluginParams){
		global $mainframe;
		$hs_base    = JURI::base().'plugins/content/ja_tabs/';
		$headtag    = array();
		$headtag[] = JHTML::_('behavior.mootools');	
		$headtag[] = "<script type='text/javascript' src='".$hs_base."ja_tabs.js' charset=\"utf-8\"></script>";				
		$headtag[] = "<link type='text/css' href='".$hs_base."ja_tabs.css' rel=\"stylesheet\"/>";
		$mainframe->addCustomHeadTag(implode("\n", $headtag));
		define("JAMOOTAB_PLUGIN_HEADTAG", TRUE);
	}
	
	function Jatabs_replacer_DIRECT($plgAttr, $plgContent)
	{		
		//params of tab
		
		$params = '';		
		$params = $this->parseParams($plgAttr);		
		
		if (isset($params['ajax']) && $params['ajax']) {
			$params['useAjax'] = "true";
		}
		else{
			//set params for ajax
			$params['useAjax'] = "false";		
		}
		
		switch ($params['type']){
			case 'content':				
				return $this->parseTabContent($plgContent, $params);				
			case 'modules':				
				return $this->parseTabModules($params);
			case 'module':
				return $this->parseTabModule($params);
			case 'articles':	
				return $this->parseTabArticle($params);	
		}		
	}	
	
	
	function 	parseTabContent($matches, $params){		
		$tabs ='';
		$_SESSION['li'] = null;
		$_SESSION['div'] = null;
		$regex = $this->getSubPattern('tab');
		preg_replace_callback($regex, array(&$this, 'wirentTabContent'), $matches);
		if($_SESSION['li']!=null){
			return $this->writeTabs($_SESSION['li'], $_SESSION['div'], $params);
		}
		return '';
	}
	
	function wirentTabContent(&$matches){
		$params = $this->parseParams($matches[1]);
		//Add color option for each tab
		$color = (isset($params['color']))?" class=\"{$params['color']}\"":"";
		$_SESSION['li'] .= "<li><h3$color><span>".$params['title']."</span></h3></li>";
		
		$_SESSION['div'] .= "<div class=\"ja-tab-content\">
								<div class=\"ja-tab-subcontent\">"
									.$matches[2]
							.'  </div>
							</div>';		
	}
	
	function parseTabModules($params){
		$module_content = ''; $lis = ''; $divs = '';
		$list =  JModuleHelper::getModules(trim($params['module']));
		for($j=0; $j<count($list); $j++){
			if($list[$j]->module!='mod_jatabs'){
				$lis  .= "<li title=\"".strip_tags($list[$j]->title)."\"><h3><span>".$list[$j]->title."</span></h3></li>";
				$divs .= "<div  class=\"ja-tab-content\">
							<div class=\"ja-tab-subcontent\">";
				if($params['useAjax']=='false'){
					$divs	.=	JModuleHelper::renderModule($list[$j]);									
				}			
				$divs .= 	'  </div>
						 </div>';
			}			
		}
		
		if($params['useAjax']=='true') {
			$params['ajaxUrl'] = JURI::base().'plugins/content/ja_tabs/ajaxloader.php?type=modules';
		}
			
		if($lis!=''){
			return $this->writeTabs($lis, $divs, $params);
		}		
		
		return ;	
	}
	
	function parseTabModule($params){
		$lis = ''; $divs = ''; $list_module=array();
		if(isset($params['modulename']) && $params['modulename']) $list_module = split(",", $params['modulename']);	
		for($i=0; $i<count($list_module); $i++){
			if ($list_module[$i]!='mod_jatabs') {
				$module =  JModuleHelper::getModule(substr(trim($list_module[$i]), 4 ));
				
				if($module && $module->id){
					$lis  .= "<li title=\"".strip_tags($module->title)."\"><h3><span>".$module->title."</span></h3></li>";
					$divs .= "<div  class=\"ja-tab-content\">
								<div class=\"ja-tab-subcontent\">";
					if($params['useAjax']=='false'){
						$divs	.=	JModuleHelper::renderModule($module);									
					}		
						
					$divs .= 	'  </div>
							 </div>';				
				}			
			}
				
		}		
		if($lis!=''){
			
			if($params['useAjax']=='true'){
				$params['ajaxUrl'] = JURI::base().'plugins/content/ja_tabs/ajaxloader.php?type=modules';
			}
			
			return $this->writeTabs($lis, $divs, $params);
		}
		return '';		
	}		
	
	function parseTabArticle($params){
		$list = null; $lis = ''; $divs = '';
		if(isset($params['ids'])){
			$list = $this->getList($params['ids'], '');
		}
		elseif (isset($params['catid'])){
			if(!isset($params['numberTabs']) || $params['numberTabs']<=0 || !is_numeric($params['numberTabs']) ) 	$params['numberTabs']  	= 0;
			$list = $this->getList('', $params['catid'], $params['numberTabs']);
		}				
						
		if($list){
			foreach ($list as $row){
				$lis  .= "<li title=\"".strip_tags($row->title)."\"><h3><span>".$row->title."</span></h3></li>";
				$divs .= "<div  class=\"ja-tab-content\">
							<div class=\"ja-tab-subcontent\">";
				
				if($params['useAjax']=='false'){
					if(!isset($params['view']) || $params['view']!='fulltext' || $row->fulltext ==''){
						$divs .= $row->introtext;
					}
					else{
						$divs .= $row->introtext.$row->fulltext;								
					}
				}													
				$divs .= 	'  </div>
						 </div>';											
			}
			
			if($params['useAjax']=='true'){
				$params['ajaxUrl'] =JURI::base().'plugins/content/ja_tabs/ajaxloader.php?type=content&view='.$params['view'];
			}
			
			return $this->writeTabs($lis, $divs, $params);
		}
		
		return '';
	}
		
	function writeTabs($lis, $divs, $params){
		global $mainframe;
		$padding = '';
		
		if(!isset($params['width']) || $params['width']<0 || (!is_numeric($params['width']) && $params['width']!='100%')) 	$params['width']  	= '100%';
		
		if(!isset($params['height']) || $params['height']<0 || (!is_numeric($params['height']) && $params['height']!='auto')) 	$params['height'] 	= 'auto';	
		
		if(!isset($params['heightTabs']) || $params['heightTabs']<0  || (!is_numeric($params['heightTabs']) && $params['heightTabs']!='auto')) {
			$params['heightTabs'] 	= 30;	
		}
				
		if (!isset($params['skipAnim']) || !in_array($params['skipAnim'], array('false', 'true'))) {
			$params['skipAnim'] = 'false';
		}	
		$params['skipAnim'] = strtolower($params['skipAnim']);
				
		if(!isset($params['animType'])) 	$params['animType'] = 'animMoveHor';
		
		
		if(!isset($params['position']) || !in_array(strtolower($params['position']), array('top', 'bottom', 'left', 'right'))){
			$params['position'] = 'top';
		}
		$params['position'] = strtolower($params['position'] );

		if(!isset($params['widthTabs']) && ($params['position']=='left' || $params['position']=='right')){
			$params['widthTabs'] 		= 150;			
		}
		
		if ($params['position']=='top' || $params['position']=='bottom'){
			$params['widthTabs']  = $params['width'];
			$width = $params['width'];
		}		
		
		if ($params['position']=='left' && is_numeric($params['widthTabs'] )) {
			$width = $params['width']-$params['widthTabs'];
		}
		
		if ($params['position']=='right' && is_numeric($params['widthTabs'] )) {			
			$padding = 'left:'.($params['widthTabs'] + 5).'px;';
			$width = $params['width']-$params['widthTabs'];			
		}
		
		if (!isset($params['style']) || $params['style']=='') {
			$params['style'] = $this->style_default;
		}	
		
		
		if (!isset($params['mouseType']) || !in_array(strtolower($params['mouseType']), array('click', 'mouseover'))) {
			$params['mouseType'] = 'click';
		}
		$params['mouseType'] = strtolower($params['mouseType']);		
		
		$override = ''; $k='';
		
		foreach($params as $k=>$value){
			if($k!='type' && $k!='module' && $k!='modulename' && $k!='widthTabs' && $k!='heightTabs' && $k!='view' && $k!='ajax')
			{
				if(is_numeric($value) || $k=='skipAnim' || $k=='useAjax'){
					$override .= $k.":".$value.",";
				}				
				else	$override .= $k.":'".$value."',";
			}
		}		
			
		if($override!=''){
			$override = substr($override, 0, strlen($override)-1);
		}

		if(!defined("JAMOOTAB_HEADTAG_".strtoupper($params['style']))){
			$headtag[] = '<link rel="stylesheet" href="'.JURI::base().'plugins/content/ja_tabs/themes/'.$params['style'].'/style.css" type="text/css" media="screen"/>';
			$mainframe->addCustomHeadTag(implode("\n", $headtag));
			define("JAMOOTAB_HEADTAG_".strtoupper($params['style']), true);
		}
		
		$id = 'myTab-'.rand(); $idtab = rand();
		
		$html = ''; $style = 'style="';
			if (is_numeric($params['height']) && $params['height']>0) {
				$style .= 'height:'.$params['height'].'px;';
			}
			if (is_numeric($params['width']) && $params['width']>0) {
				$style .= 'width:'.$params['width'].'px;';
			}
			else{
				$style .= 'width:100%;';
			}
			$style .= '"';
			
			$html .= '<div class="ja-tabswrap '.strtolower($params['style']).'" '.$style.'>';
			
			$html.=	 '	<div  id="'.$id.'" class="ja-container" >';
			
			if($params['position']=='top'){	
				/* set style for title top */
				$styleTop = 'style="';				
				if (is_numeric($params['heightTabs']) && $params['heightTabs']>0) {
					$styleTop .= 'height:'.$params['heightTabs'].'px;';
				}				
				$styleTop .= '"';		
									
				$html .= '	<div class="ja-tabs-title-'.$params['position'].'" '.$styleTop.'>';
			}
			elseif($params['position']!='bottom'){
				/* set style for title top */
				$styleMiddle = 'style="';
								
				if (is_numeric($params['widthTabs']) && $params['widthTabs']>0) {
					$styleMiddle .= 'width:'.$params['widthTabs'].'px;';
				}
				else{
					$styleMiddle .= 'width:'.$params['widthTabs'].';';
				}
				$styleMiddle .= '"';	
				
				$html.=	 '	<div class="ja-tabs-title-'.$params['position'].'" '.$styleMiddle.'>';
			}			

			$style = '';
			if (is_numeric($params['height']) && $params['height']>0) {
				$style = 'style="height:0px;"';
			}		

			if ($params['position']=='bottom') {
					$html.=	 '<div class="ja-tab-panels-'.$params['position'].'" '.$style.'>'
							.	$divs
							.'</div>
							<div class="ja-tabs-title-'.$params['position'].'" >			
								<ul class="ja-tabs-title">'
									.$lis
							.	'</ul>
							 </div>						
							';	
			}	
			else{	
					$html.=	 '			
								<ul class="ja-tabs-title">'
									.$lis
							.	'</ul>
							</div>						
							<div class="ja-tab-panels-'.$params['position'].'" '.$style.'>'
							.	$divs
							.'</div>';
			}
									
			$html.=	 '	</div>
					</div>';
			$html .= '<script type="text/javascript" charset="utf-8">
						window.addEvent("load", init);
						function init() {
							myTabs1 = new JATabs("'.$id.'", {'.$override.'});							
						}
						//new JATabs("'.$id.'", {'.$override.'});													
				     </script>';			
		return $html;
	}
	
	function getList($ids='', $catid='', $limit=0)
	{
		global $mainframe;
		$db 	=& JFactory::getDBO();
		$user 	=& JFactory::getUser();
		$aid	= $user->get('aid', 0);

		$contentConfig	= &JComponentHelper::getParams( 'com_content' );
		$noauth			= !$contentConfig->get('shownoauth');

		jimport('joomla.utilities.date');
		$date = new JDate();
		$now = $date->toMySQL();

		$nullDate = $db->getNullDate();

		// query to determine article count
		$query = 'SELECT a.*' .			
			' FROM #__content AS a' .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid';
		$query .=	' WHERE a.state = 1 ' .
			($noauth ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
			' AND (a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' ) ' .
			' AND (a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )' .				
			' AND cc.section = s.id' .
			' AND cc.published = 1' .
			' AND s.published = 1';
		if($ids!=''){
			$query .= "\n AND a.id in ($ids)";
		}
		if($catid != '') {
			$query .=	" AND a.catid=$catid";
		}						
		$query .= ' ORDER BY a.ordering ' ;		
		if($catid != '' && $limit>0) {
			$query .=	"LIMIT 0, $limit";
		}

		$db->setQuery($query);
		$rows = $db->loadObjectList();		
		  
		jimport('joomla.plugin.helper');
		JPluginHelper::importPlugin('content');
		
		$app = &JFactory::getApplication();
		$pparams = new JParameter('');
		
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
  
  		for($i=0;$i<count($rows);$i++) {
  		  $rows[$i]->text = $rows[$i]->introtext;
  		  $app->triggerEvent('onPrepareContent', array (& $rows[$i], & $params, $limitstart));
   		  $rows[$i]->introtext = $rows[$i]->text;
      	}
      	
		return $rows;
	}
	
	function getPattern ($tag) {
	 $regex = '#{'.$tag.' ([^}]*)}([^{]*){/'.$tag.'}#m';

	  return $regex;
	}

	function getSubPattern ($tag) {
	  $regex = '#\['.$tag.' ([^\]]*)\]([^\[]*)\[/'.$tag.'\]#m';
	  return $regex;
	}
	
	function parseParams($params) {
		$params = html_entity_decode($params, ENT_QUOTES);
		$regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
		preg_match_all($regex, $params, $matches);
		
		 $paramarray = null;
		 if(count($matches)){
			$paramarray = array();
				for ($i=0;$i<count($matches[1]);$i++){ 
				  $key = $matches[1][$i];
				  $val = $matches[3][$i]?$matches[3][$i]:($matches[4][$i]?$matches[4][$i]:$matches[5][$i]);
				  $paramarray[$key] = $val;
				}
		  }
		  return $paramarray;
	}
}
?>