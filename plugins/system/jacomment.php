<?php
/*
# ------------------------------------------------------------------------
# JA System Comment plugin for Joomla 1.5
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

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.plugin.plugin' );

/**
 * JAComment System Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.5
 */
class plgSystemJAComment extends JPlugin
{
    var $_plgCode 		= "#{jacomment(.*?) contentid=(.*?) option=(.*?) contenttitle=(.*?)}#i";
    var $_plgAddButton 	= "#{jacomment_addbutton(.*?) link=(.*?)}#i";     
    var $_plgCount 		= "#{jacomment_count(.*?) contentid=(.*?) option=(.*?) contenttitle=(.*?)}#i";  
    var $_plgStart   	= "\n\n<!-- JAComment starts -->\n";
    var $_plgEnd     	= "\n<!-- JAComment ends -->\n\n";
	var $_option	 	= "com_content";	
    var $_print		 	= 0;
	function plgSystemJAComment( &$subject ){
		parent::__construct( $subject );

		// load plugin parameters
        $this->plugin = &JPluginHelper::getPlugin('system', 'jacomment');
        //print_r($this->plugin);die();
        $this->plgParams = new JParameter($this->plugin->params); 
        //print_r($this->plgParams);die();
	}
	
    function check(){
        global $mainframe; 
        if ( $mainframe->isAdmin() ) { return; }
        
        if (!isset($this->plugin)) return;
        
        $_body = JResponse::getBody();
        
        //check if show plugin code
        $found = preg_match($this->_plgCode, $_body);         
                
        if ($found) {        	
            return $_body;   
        }
        
        //check if show plugin add button
    	$found = preg_match($this->_plgAddButton, $_body);         
                
        if ($found) {        	
            return $_body;   
        }
        
        
        return false;
    }
    
    function onAfterRoute()
    {
		if(file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php')){
			global $mainframe, $option;	
			if ( $mainframe->isAdmin() ) { return; }
			$option         = JRequest::getCmd('option');
			$print	= JRequest::getCmd('print', 0);			
			$this->_option	= $option; 
			$this->_print	= $print;			
			if($option == 'com_content' || $print){
				return;	
			}
			
			if($option != 'com_content'){				
				//add style for all componennt
				JHTML::stylesheet('ja.comment.css', 'components/com_jacomment/asset/css/');
				//override for all component
           		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.comment.css')){
					JHTML::stylesheet('ja.comment.css', 'templates/'.$mainframe->getTemplate().'/css/');
				}
				
				require_once(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'comments.php');
		        $model = new JACommentModelComments();			        			            						   														
				$theme = $model->getParamValue( 'layout', 'theme' ,'default');
				$session = &JFactory::getSession();
				if(JRequest::getVar("jacomment_theme", '')){
					jimport( 'joomla.filesystem.folder' );
					$themeURL = JRequest::getVar("jacomment_theme");
					if(JFolder::exists('components/com_jacomment/themes/'.$themeURL)){
						$theme =  $themeURL;						
					}
					$session->set('jacomment_theme', $theme);			
				}else{
					if($session->get('jacomment_theme', null)){
						$theme = $session->get('jacomment_theme', $theme);
					}
				}					
													 					
				if(!defined('JACOMMENT_GLOBAL_CSS')){																	 
					global $mainframe;			      
					//add style for japopup
					if(file_exists('components/com_jacomment/asset/css/ja.popup.css')){			      
						JHTML::stylesheet('ja.popup.css', 'components/com_jacomment/asset/css/');
					}
					//override template for japopup in template
				    if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.popup.css')){
					    JHTML::stylesheet('ja.popup.css', 'templates/'.$mainframe->getTemplate().'/css/');
					}

					//add style for all componennt
					if(file_exists('components/com_jacomment/asset/css/ja.comment.css')){
						JHTML::stylesheet('ja.comment.css', 'components/com_jacomment/asset/css/');
					}
					//override for all component
            		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.comment.css')){
						JHTML::stylesheet('ja.comment.css', 'templates/'.$mainframe->getTemplate().'/css/');
					}
					
					//add style only IE for all component
					if(file_exists('components/com_jacomment/asset/css/ja.ie.php')){
						JHTML::stylesheet('ja.ie.php', 'components/com_jacomment/asset/css/');
					}            							
            		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.ie.php')){
					    JHTML::stylesheet('ja.ie.php', 'templates/'.$mainframe->getTemplate().'/css/');
					}					
					
            		//add style of template for component
					if(file_exists('components/com_jacomment/themes/'.$theme.'/css/style.css')){					
            			JHTML::stylesheet('style.css', 'components/com_jacomment/themes/'.$theme.'/css/');
					}
					if(file_exists(JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS."com_jacomment".DS."themes".DS. $theme .DS."css".DS."style.css")){		
						JHTML::stylesheet('style.css', 'templates/'.$mainframe->getTemplate().'/html/com_jacomment/themes/'.$theme.'/css/');	 
					}
					
					if(file_exists(JPATH_BASE.DS.'components/com_jacomment/themes/'.$theme.'/css/style.ie.css')){
            			JHTML::stylesheet('style_ie.css', 'components/com_jacomment/themes/'.$theme.'/css/');
					}	
					if(file_exists(JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS."com_jacomment".DS."themes".DS. $theme .DS."css".DS."style.ie.css")){		
						JHTML::stylesheet('style.ie.css', 'templates/'.$mainframe->getTemplate().'/html/com_jacomment/themes/'.$theme.'/css/');	 
					}
					
					$lang =& JFactory::getLanguage();											
					if ( $lang->isRTL() ) {						
						if(file_exists(JPATH_BASE.DS.'components/com_jacomment/asset/css/ja.popup_rtl.css')){															
							JHTML::stylesheet('ja.popup_rtl.css', 'components/com_jacomment/asset/css/');	
						}					
						if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.popup_rtl.css')){															
							JHTML::stylesheet('ja.popup_rtl.css', 'templates/'.$mainframe->getTemplate().'/css/');	
						}
						if(file_exists(JPATH_BASE.DS.'components/com_jacomment/asset/css/ja.comment_rtl.css')){						
							JHTML::stylesheet('ja.comment_rtl.css', 'components/com_jacomment/asset/css/');			
						}															
						if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.comment_rtl.css')){															
							JHTML::stylesheet('ja.comment_rtl.css', 'templates/'.$mainframe->getTemplate().'/css/');	
						}
						
						//add style only IE for all component
						if(file_exists(JPATH_BASE.DS.'components/com_jacomment/asset/css/ja.ie_rtl.php')){
							JHTML::stylesheet('ja.ie.php', 'components/com_jacomment/asset/css/');            		
						}					
	            		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.ie_rtl.php')){
						    JHTML::stylesheet('ja.ie_rtl.php', 'templates/'.$mainframe->getTemplate().'/css/');
						}					
						
						if(file_exists(JPATH_BASE.DS.'components/com_jacomment/themes/'.$theme.'/css/style_rtl.css')){
							JHTML::stylesheet('style_rtl.css', 'components/com_jacomment/themes/'.$theme.'/css/');
						}
						if(file_exists(JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS."com_jacomment".DS."themes".DS. $theme .DS."css".DS."style_rtl.css")){		
							JHTML::stylesheet('style_rtl.css', 'templates/'.$mainframe->getTemplate().'/html/com_jacomment/themes/'.$theme.'/css/');	 
						}

						if(file_exists(JPATH_BASE.DS.'components/com_jacomment/themes/'.$theme.'/css/style.ie_rtl.css')){
	            			JHTML::stylesheet('style_ie_rtl.css', 'components/com_jacomment/themes/'.$theme.'/css/');
						}	
						if(file_exists(JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS."com_jacomment".DS."themes".DS. $theme .DS."css".DS."style.ie_rtl.css")){		
							JHTML::stylesheet('style.ie_rtl.css', 'templates/'.$mainframe->getTemplate().'/html/com_jacomment/themes/'.$theme.'/css/');	 
						}
					}		
										        			            						   										
					$enableSmileys = $model->getParamValue( 'layout', 'enable_smileys' ,0);
										
					if($enableSmileys){
						$smiley 	= $model->getParamValue( 'layout', 'smiley' , 'default');
						$style = '
						       #jac-wrapper .plugin_embed .smileys{
						            top: 17px;
						        	background:#ffea00;
						            clear:both;
						            height:84px;
						            width:105px;
						            padding:2px 1px 1px 2px !important;
						            position:absolute;
						            z-index:51;
						            -webkit-box-shadow:0 1px 3px #999;box-shadow:1px 2px 3px #666;-moz-border-radius:2px;-khtml-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;
						        }        
						        #jac-wrapper .plugin_embed .smileys li{
						            display: inline;
						            float: left;
						            height:20px;
						            width:20px;
						            margin:0 1px 1px 0 !important;
						            border:none;
						            padding:0 !important;
						        }
						        #jac-wrapper .plugin_embed .smileys .smiley{
						            background: url('.JURI::base().'components/com_jacomment/asset/images/smileys/'.$smiley.'/smileys_bg.png) no-repeat;
						            display:block;
						            height:20px;
						            width:20px;
						        }
						        #jac-wrapper .plugin_embed .smileys .smiley:hover{
						            background:#fff;
						        }
						        #jac-wrapper .plugin_embed .smileys .smiley span{
						            background: url('.JURI::base().'components/com_jacomment/asset/images/smileys/'.$smiley.'/smileys.png) no-repeat;
						            display: inline;
						            float: left;
						            height:12px;
						            width:12px;
						            margin:4px !important;
						        }
						        #jac-wrapper .plugin_embed .smileys .smiley span span{
						            display: none;
						        } 
						        #jac-wrapper .comment-text .smiley {
						            font-family:inherit;
									font-size:100%;
									font-style:inherit;
									font-weight:inherit;
									text-align:justify;
						        }
						        .comment-text .smiley span{
						            background: url('.JURI::base().'components/com_jacomment/asset/images/smileys/'.$smiley.'/smileys.png) no-repeat scroll 0 0 transparent;
									display:inline;
									float:left;
									height:12px;
									margin:4px !important;
									width:12px;
						        }
						        .comment-text .smiley span span{
						            display:none;
						        }
						';						
						
						$doc = & JFactory::getDocument();
						$doc->addStyleDeclaration($style);
						define('JACOMMENT_GLOBAL_CSS_SMILEY', true);
					}
					
					if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.comment.custom')){
						JHTML::stylesheet('ja.comment.custom', 'templates/'.$mainframe->getTemplate().'/css/');
					}
					define('JACOMMENT_GLOBAL_CSS', true);
				}
								
				if(!defined('JACOMMENT_GLOBAL_JS')){					
					JHTML::script('jquery-1.3.1.js', 'components/com_jacomment/asset/js/');
					JHTML::script('ja.comment.js', 'components/com_jacomment/asset/js/');
					JHTML::script('ja.popup.js', 'components/com_jacomment/asset/js/');  
					define('JACOMMENT_GLOBAL_JS', true);
				}
				
				if(!defined('JACOMMENT_PLUGIN_ATD')){
					JHTML::stylesheet('atd.css', 'components/com_jacomment/asset/css/');
					JHTML::script('jquery.atd.js', 'components/com_jacomment/libs/js/atd/');
					JHTML::script('csshttprequest.js', 'components/com_jacomment/libs/js/atd/');
					JHTML::script('atd.js', 'components/com_jacomment/libs/js/atd/');
											   
					define('JACOMMENT_PLUGIN_ATD', true);            
				} 
			}
		}
    }
    
	function onAfterRender()
	{
		$option = $this->_option;
		if($option == 'com_content' || $this->_print){
				return;	
		}
		if(file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php') && $option != "com_content"){			
			global $mainframe;
			if ( $mainframe->isAdmin() ) { return; }
			
			$tmpl = 'default';
			$plugin = $this->plugin;
			$plgParams = $this->plgParams;
			$_body = $this->check();
			
			require_once(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'comments.php');
						
			$model = new JACommentModelComments();			        			            						   														
			$theme = $model->getParamValue( 'layout', 'theme' , 'default');
			$session = &JFactory::getSession();
			if(JRequest::getVar("jacomment_theme", '')){
				jimport( 'joomla.filesystem.folder' );
				$themeURL = JRequest::getVar("jacomment_theme");
				if(JFolder::exists('components/com_jacomment/themes/'.$themeURL)){
					$theme =  $themeURL;						
				}
				$session->set('jacomment_theme', $theme);			
			}else{
				if($session->get('jacomment_theme', null)){
					$theme = $session->get('jacomment_theme', $theme);
				}
			}
			
			//print_r($plgParams);die();  						              
			if ($_body) { 			    
				require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'helpers'.DS.'jahelper.php');
				$helper = new JACommentHelpers();
				
				//show button add new comment and count comment.
				if(strpos($_body, "jacomment_addbutton") || strpos($_body, "jacomment_count")){										
	                preg_match_all($this->_plgAddButton, $_body, $matches);
	                preg_match_all($this->_plgCount, $_body, $matchesCount);	                
	                
	                $plugInType = "system";
	                //change plugin button with button
	                for($i =0 ; $i< count($matches[0]); $i++){							                							
						if(isset($matches[2][$i])){								
							$links = $matches[2][$i];
						}else{
							$links = "";
						}					
						$typeDisplay = "onlyButton"; 							
						ob_start ();       
						require $helper->jaLoadBlock("comments/getbutton.php");  
		                //require(JPATH_BASE.DS.'components'.DS.'com_jacomment'.DS.'views'.DS.'comments'.DS.'tmpl'.DS.'getbutton.php');
		                $output = ob_get_contents();
						ob_end_clean(); 					                	                  			                  		    
		                 
						$_body = str_replace($matches[0][$i], $output, $_body);
	                		                	
	                }				
	                	             				
	                //change plugin count with count
					for($i =0 ; $i< count($matchesCount[0]); $i++){							                							
						//get content id
						if(isset($matchesCount[2][$i])){								
							$id = $matchesCount[2][$i];
						}else{
							$id = "";
						}			
						//get content option
						if(isset($matchesCount[3][$i])){								
							$option = $matchesCount[3][$i];
						}else{
							$option = "";
						}								
						//get content title
						if(isset($matchesCount[4][$i])){								
							$title = addslashes($matchesCount[4][$i]);
						}else{
							$title = "";
						}
						
						$typeDisplay = "onlyCount"; 							
						ob_start ();
						require $helper->jaLoadBlock("comments/getbutton.php");             
		                //require(JPATH_BASE.DS.'components'.DS.'com_jacomment'.DS.'views'.DS.'comments'.DS.'tmpl'.DS.'getbutton.php');
		                $output = ob_get_contents();
						ob_end_clean(); 					                	                  			                  		    
		                 
						$_body = str_replace($matchesCount[0][$i], $output, $_body);
	                		                	
	                }
	                
				}
				//show all comment of this items when call
				else{		
					ob_start ();											
					$session = &JFactory::getSession();
	                // Put a value in a session var
	                $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	                $protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]),0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")).$s;
	                $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	                $webUrl = $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];				                                                                  
					$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();				
					$webUrl = str_replace($url,"",$webUrl);
					
					$session->set('commenturl', $webUrl);                                                 										
					
					$GLOBALS['jacconfig'] = array(); 
					JACommentHelpers::get_config_system();
					global $jacconfig;
					
					if(isset($jacconfig['general']) && $jacconfig['general']->get('is_comment_offline', 0)){
						if(!JACommentHelpers::check_access()) return ;
					}
										
					require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'helpers'.DS.'config.php');
					preg_match_all($this->_plgCode, $_body, $matches);
						
					$lists['commenttype'] 	= 1;
					if(isset($matches[3][0])){								
						$lists['contentoption'] = $matches[3][0];
					}else{
						$lists['contentoption'] = '';
					}
					if(isset($matches[2][0])){
						$lists['contentid'] 	= $matches[2][0];	
					}else{
						$lists['contentid'] 	= 0;
					}
					if(isset($matches[4][0])){						
						$lists['contenttitle']  = addslashes($matches[4][0]);                                    						
					}else{
						$lists['contenttitle']  = "";
					}				
					$lists['jacomentUrl'] 	= $webUrl;						
	            	?>
	            	<!-- BEGIN - load blog head -->
					<?php require_once $helper->jaLoadBlock("comments/head.php");	?>
					<!-- END   - load blog head -->
					<?php if(($jacconfig['layout']->get('enable_addthis')==1) || ($jacconfig['layout']->get('enable_addtoany')==1) || ($jacconfig['layout']->get('enable_tweetmeme')==1)){	?>	 	   	
						  <div id="jac-social-links">
						    <ul>
								<?php							
									if($jacconfig['layout']->get('enable_addthis')==1)												        	
							        	echo "<li>" .$jacconfig['layout']->get('custom_addthis'). "</li>";
							        if($jacconfig['layout']->get('enable_addtoany')==1)	
							        	echo "<li>" .$jacconfig['layout']->get('custom_addtoany'). "</li>";		       
							        if($jacconfig['layout']->get('enable_tweetmeme')==1)
							        	echo "<li>" .$jacconfig['layout']->get('custom_tweetmeme'). "</li>";
						        ?>
						    </ul>
						  </div>	  
					<?php }?>
					<div id="jac-wrapper" class="clearfix"></div>		
	            	<script language="javascript">
		            	var url = window.location.hash;;
		            	c_url = url.split('#');
		            	id = 0;
		            	tmp = 0;
		            	if(c_url.length >= 1){		
		            		for(i=1; i< c_url.length; i++){			
		            			if(c_url[i].indexOf("jacommentid:") >-1){				
		            				tmp = c_url[i].split(':')[1];				
		            				if(tmp != ""){									
		            					id = parseInt(tmp, 10);
		            				}
		            			}
		            		}
		            	}
		            	
		            	url = "<?php echo $url; ?>index.php?tmpl=component&option=com_jacomment&view=comments&contentoption=<?php echo $lists['contentoption'];?>&contentid=<?php echo $lists['contentid'];?>&ran=" + Math.random();	            	
		            		            	        
		            	if(id != 0){
							url += "&currentCommentID=" + id;
		            	}	
		            								            			           
						new Ajax(url, {method: 'get', onComplete: function(text){
							$('jac-wrapper').innerHTML = text;																		 						
							//$('jacommentid:'+id).getPosition();
							moveBackground(id, '<?php echo JURI::root();?>');
							<?php //check exist javoice component - add function
							if(file_exists(JPATH_SITE.DS.'components'.DS.'com_javoice'.DS.'javoice.php')){
							?>
							if (typeof jav_jacomment=="function"){	
								jav_jacomment();
							}								
							<?php }?>																																																					
							jac_auto_expand_textarea();												
						}}).request();				
					</script>
				<?php 
					$output = ob_get_contents();
					ob_end_clean(); 
					
					$output = $this->_plgStart.$output.$this->_plgEnd; 
					$_body = preg_replace($this->_plgCode, $output, $_body);
				}?>
			<?php            									
			}			
			if ( $_body ) {
				JResponse::setBody( $_body );
			}  
			return true;
		}
	}
    
    
    
}
?>