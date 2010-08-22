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
 * @package	JAComment
  */
class JACommentViewImexport extends JAView {
	/**
	 * 
	 */
	function display($tmpl = null) {
		global $db;       
		 
        $group = JRequest::getVar ( 'group', 'import' );        
        $type = JRequest::getVar('type');                
        $task = JRequest::getVar ( 'task', null, 'default', 'cmd' );
        
        if($task == "showcomment"){
        	$this->setLayout("showlist");
        	
        	$this->showComment();
        	$source = JRequest::getVar( 'source' ,'');
	        $this->assignRef ( 'source', $source );	        	                			        
        }else if($task == "open_content"){
        	$this->showcontent();	
        }else{        	
        	$this->setLayout ( $group );
        	$model = &JModel::getInstance('Imexport', 'JACommentModel');
	        $OtherCommentSystems = $model->JAOtherCommentSystem();
	
	        $tables = $model->showTables();
	    
	        foreach ($tables as $table) {
	            for($i=0, $n=count($OtherCommentSystems); $i < $n; $i++ ) {
	                $table_chk = str_replace( '#_', '', $OtherCommentSystems[$i]['table'] );
	                
	                if (preg_match('/'.$table_chk.'$/i', $table)) {
	                    $OtherCommentSystems[$i]['status'] = true;
	                    $OtherCommentSystems[$i]['total'] = $model->totalRecord($table);
	                }     
	            }
	        }
	        
	        $this->assignRef ( 'OtherCommentSystems', $OtherCommentSystems );
	        
	        $source = JRequest::getVar( 'source' ,'');
	        $this->assignRef ( 'source', $source );
			$this->assignRef ( 'group', $group );
        	
        }                	       
		
		parent::display ( $tmpl );		        
	}
	
	function showComment(){
		JRequest::setVar('layout','import');
		
		$model = & $this->getModel ( 'imexport' );
		
        $source = JRequest::getVar( 'source' );
        
		jimport('joomla.filesystem.file'); 
        
		if(isset($_FILES[$source]) && $_FILES[$source]['name']!='' && strtolower(substr($_FILES[$source]['name'], -3, 3))=='xml'){
		    $file = JPATH_COMPONENT_ADMINISTRATOR.DS.'temp'.DS.substr($_FILES[$source]['name'], 0, strlen($_FILES[$source]['name'])-4).time().rand().substr($_FILES[$source]['name'], -4, 4);
            
			if(JFile::upload($_FILES[$source]['tmp_name'], $file)){
			    unset($_FILES[$source]);
                
                $xml =& JFactory::getXMLParser( 'simple' );
     
                $xml->loadFile( $file );
                $out =& $xml->document; 
				
                //check is valid xml document
                if($out == false){
                	JError::raiseNotice(1, JText::_('ERROR OCCURRED! CAN NOT IMPORT THE DATA. PLEASE BROWSE A VALID XML FILE.'));
        			return JController::setRedirect("index.php?option=com_jacomment&view=imexport&group=import", '');        	
                }else{                	
                		$allComments = $out->children();                		
               			if($allComments[0]->name() != "article"){
               				JError::raiseNotice(1, JText::_('Please select xml file of disqus comments.'));
                   	 		//return $this->setRedirect("index.php?option=com_jacomment&view=imexport&group=import");
                   	 		return JController::setRedirect("index.php?option=com_jacomment&view=imexport&group=import");		 	
               			}
                		                		
	                	//disqus
	                	$site_url = JRequest::getVar("site_url","");	                	
	                	$items 	  = array();
	                	$i = 0;
	                	$rows = array();                          	    
		                foreach ( $allComments as $blogpost) {	                			                    		                	
		                	foreach ($blogpost->children() as $comments) {	                    	
		                        $other[$comments->name()] = $comments->data();		                         		                        		                        	    										                                                                    
		                        foreach ($comments->children() as $key => $value) {	                            
		                        	$comment[$key] = $value->children();                                                    
		                            foreach ($comment[$key] as $v) {	                            	
		                                if($site_url == "" || strpos($other["url"], $site_url) !== false){
		                                	$rows[$key][$v->name()] = $v->data();		                                		
		                                }		                            			                                		                                		                                                                
		                            }
		                        }
		                    }
		                    if($site_url == "" || strpos($other["url"], $site_url) !== false){
		                    	$items[$other["url"]] = $rows;
		                    }
		                    $rows = array();
		                }		                		                		                		                	                		                
		                if(!$items || count($items) <1){
		                	JError::raiseNotice(1, JText::_('No comment in xml file was found.'));               	
               				return JController::setRedirect("index.php?option=com_jacomment&view=imexport&group=import"); 
		                }
		                $this->assign("items", $items);
		                $this->assign("allComponents", $this->getAllComponent());
		                //print_r($this->getAllComponent());die();
		                //show list comment to choise:		                		                		                                	
           		}                   	                	                	               
           }else{
           	   	JError::raiseNotice(1, JText::_('ERROR OCCURRED! CAN NOT IMPORT THE DATA.'));
               	//return $this->setRedirect("index.php?option=com_jacomment&view=imexport&group=import");
               	return JController::setRedirect("index.php?option=com_jacomment&view=imexport&group=import");                             	                                 
           }
		}else{
            JError::raiseNotice(1, JText::_('ERROR OCCURRED! CAN NOT IMPORT THE DATA. PLEASE BROWSE AN XML FILE.'));
            return JController::setRedirect("index.php?option=com_jacomment&view=imexport&group=import");  
        }	
	}
	
	function checkExistComponent($componentOption){
		$model = &JModel::getInstance('Imexport', 'JACommentModel');
		return $model->checkExistComponent($componentOption);	
	}
	
	function getAllComponent(){
		$model = &JModel::getInstance('Imexport', 'JACommentModel');		
		return $model->getAllComponent();
	}
	
	function getComponentFromAriticle($link, $isExitComMyblog){
		$model = &JModel::getInstance('Imexport', 'JACommentModel');		
		return $model->getComponentFromAricleLink($link, $isExitComMyblog);
	}
	
	function getMyBlogLink($id){
		$model 		= &JModel::getInstance('Imexport', 'JACommentModel');
		$permalink	= $model->getMyBlogLink($id);
		return JRoute::_("index.php?option=com_myblog&show={$permalink}&Itemid={$id}");			
	}
	
	function getTabs() {
		global $option;
		
		$group = JRequest::getVar ( 'group', '' );
		$tabs = '<div class="submenu-box">
						<div class="submenu-pad">
							<ul id="submenu" class="configuration">
								<li><a href="index.php?option=' . $option . '&view=imexport&group=import"';
		if ($group == 'import' || $group == '') {
			$tabs .= ' class="active" ';
		}
		$tabs .= '>';
		$tabs .= JText::_ ( 'Import Data' ) . '</a></li>';
								
		$tabs .= '<li><a href="index.php?option=' . $option . '&view=imexport&group=export"';
		if ($group == 'export') {
			$tabs .= ' class="active" ';
		}
		$tabs .= '>';		
		$tabs .= JText::_ ( 'Export Data' ) . '</a></li>';
		
		$tabs .= '				</ul>
							<div class="clr"></div>
						</div>
					</div>
					<div class="clr"></div>';		
		return $tabs;
	}
	
	
	function showcontent(){
		$model 			= &JModel::getInstance('Imexport', 'JACommentModel');
		$component[]	= "com_content";		
		$isExitComBlog 	= $model->checkComBlog();
		if($isExitComBlog)
			$component[]	= "com_myblog";	
		$this->assign("component", $component);				
	}
}
?>