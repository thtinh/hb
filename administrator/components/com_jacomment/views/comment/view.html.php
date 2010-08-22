<?php
/*
# ------------------------------------------------------------------------
# JA Comments component for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2004-2010 JoomlArt.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2. CSS / JS are Copyrighted Commercial,
# bound by Proprietary License of JoomlArt. For details on licensing, 
# Please Read Terms of Use at http://www.joomlart.com/terms_of_use.html.
# Author: JoomlArt.com
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# Redistribution, Modification or Re-licensing of this file in part of full, 
# is bound by the License applied. 
# ------------------------------------------------------------------------
*/
// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.view' );

/**
 * @package		Joomla
 * @subpackage	Contacts
 */
class jacommentViewcomment extends JAView {
	function display($tpl = null) {
		
		$layout = JRequest::getVar ( 'layout', 'statistic' );
		switch ($layout) {
			case 'statistic' :
				$this->statistic ();
				break;
			case 'license' :
				$this->displayLicense ();
				break;
			case 'verify' :
				$this->form ();
				break;	
			default :
				$this->statistic ();
				break;
		}
		$this->setLayout ( $layout );
		parent::display ( $tpl );
	
	}
	function statistic() {
        require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'comments.php'); 
        $model = new JACommentModelComments();
        
        $lastvisitDate = $_SESSION['__default']['user']->lastvisitDate;
        
        // calculate
        $total_new = $model->getTotal(' AND date > "'.$lastvisitDate.'"');
        $total_today = $model->getTotal(' AND TO_DAYS(NOW()) = TO_DAYS(date)');
        $total_30day = $model->getTotal(' AND TO_DAYS(NOW()) - TO_DAYS(date) <= 30');
        $total = $model->getTotal('');
        
        // assign
        $this->assign('total_new', $total_new);
        $this->assign('total_today', $total_today);
        $this->assign('total_30day', $total_30day);
        $this->assign('total', $total);
        
        
		
	}
	function displayItems() {
	
	}
	
	function form() 
	{
		$post = JRequest::get ( 'post', JREQUEST_ALLOWHTML );
		if(count($post) > 0 && $post['email'] != '' && $post['payment_id'] != '') {
			$objVerify = new JACommentLicense();
			$objVerify->verify_license();
		}
		$row = $this->getInfo();
		$this->assignRef ( 'row', $row );
	}
	
	function getInfo()
	{
		global $jacconfig;
		$row["email"] = "";
		$row["payment_id"] = "";
		if(isset($jacconfig["license"])){
			$row["email"] 		=  $jacconfig["license"]->get("email","");
			$row["payment_id"]  =  $jacconfig["license"]->get("payment_id","");
		}				
		return $row;
	}
	
	function displayLicense() {				
		$row = $this->getInfo();
		$this->assignRef ( 'row', $row );
	}
	
	function menu() {
		global $JACVERSION;
		
		$latest_version ='';
		$version_link = JACommentHelpers::get_Version_Link();
		$layout = JRequest::getVar ( 'layout', 'statistic' );
		$cid = JRequest::getVar ( 'cid' );
		if (is_array ( $cid )) {
			JArrayHelper::toInteger ( $cid );
			$cid = $cid [0];
		}
		
		$temp_latest_version = $this->get ( 'LatestVersion' );
		if (count($temp_latest_version)>0)
		{
			$latest_version =isset($temp_latest_version[0])?$temp_latest_version[0]:'';
			$version_link['latest_version']['info'] = 'http://wiki.joomlart.com/wiki/JA_Comment/Overview';
			$version_link['latest_version']['upgrade'] = 'http://www.joomlart.com/forums/downloads.php?do=cat&id=163';
		}
		else 
		{
			$version_link['latest_version']['info'] = '';
			$version_link['latest_version']['upgrade'] = '';
		}
		$current_version = $JACVERSION;
		?>
		<div id="comment-header-search-left">
			<ul id="submenu">
				<li><a href="index.php?option=com_jacomment&view=comment&layout=statistic"
					class="<?php
					if ($layout == null || $layout == 'statistic')
						echo 'active'?>">
											<?php
					echo JText::_ ( 'Statistics' );
					?>
										</a></li>
				<li><a href="index.php?option=com_jacomment&amp;view=comment&amp;layout=support"
					class="<?php
					if ($layout == 'support')
						echo 'active'?>">
											<?php
					echo JText::_ ( 'Support' );
					?>
										</a></li>
				<li><a href="index.php?option=com_jacomment&view=comment&amp;layout=license"
					class="<?php
					if ($layout == 'license' || $layout == 'verify')
						echo 'active'?>">
											<?php
					echo JText::_ ( 'License' );
					?>
										</a></li>
			</ul>
		</div>
		<div id="comment-header-search-right">
			<?php 
				if($latest_version==''){
					 echo JText::_ ( 'Version' ). ' <b>'.$current_version.'</b>';
				}elseif($latest_version!='' && trim($latest_version)==trim($current_version)){
					echo JText::_('Your version'). ': <b><a href="'.$version_link['current_version']['info'].'" target="_blank">'.$current_version.'</a></b>&nbsp;&nbsp;'.JText::_ ( 'Latest version' ).': <b><a href="'.$version_link['latest_version']['info'].'" target="_blank">'.$latest_version.'</a></b>&nbsp;&nbsp;<font color="Blue"> <i>('.JText::_ ( 'System running the latest version.' ).')</i></font>'; 
				}elseif($latest_version!='' && $latest_version!=$current_version){
					echo JText::_('Your version'). ': <b><a href="'.$version_link['current_version']['info'].'" target="_blank">'.$current_version.'</a></b>&nbsp;&nbsp;'.JText::_ ( 'Latest version' ).': <b>';
					echo isset($version_link['latest_version'])?'<a href="'.$version_link['latest_version']['info'].'" target="_blank">'.$latest_version.'</a>':$latest_version;echo '</b>&nbsp;&nbsp;<span style="background-color:rgb(255,255,0);color:Red;font-weight:bold;">'.JText::_('New version available!'). '</span> ';
					if (isset($version_link['latest_version'])) echo '<a target="_blank" href="'.$version_link['latest_version']['upgrade'].'" title="'.JText::_('Click here to download latest version').'">'.JText::_ ( 'Upgrade now' ).'</a>';
				}?>						
		</div>
	<?php
	}
}	
