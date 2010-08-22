<?php
/*
# ------------------------------------------------------------------------
# JA Comment plugin for Joomla 1.5
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
jimport ( 'joomla.application.component.model' ); 

/**
 * JAComment Content plugin
 */
class plgContentJAComment extends JPlugin
{
    var $_plgCode = "#{JAComment(.*?)}#i";
    var $_plgCodeDisable = "#{JAComment(\s*)disable}#i";
    var $_plgStart   = "\n\n<!-- JAComment starts -->\n";
    var $_plgEnd     = "\n<!-- JAComment ends -->\n\n";
    //: {jacomment contentid=xx option=xxxxx contentittle=''}    
    function plgContentJAComment( &$subject, $config )
    {
        parent::__construct( $subject, $config );

        $this->plugin = &JPluginHelper::getPlugin('content', 'jacomment');
        $this->plgParams = new JParameter($this->plugin->params);  
    }
        
    //display top of content
    function onBeforeDisplayContent( &$article, &$params, $limitstart ){
		global $mainframe;
		if ( $mainframe->isAdmin() ) { return; }
		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php')){
			return '';	
		}
		$option = JRequest::getCmd('option');
		$print	= JRequest::getCmd('print', 0);		
		if($option != "com_content" || $print >0){
			return '';    			
		}
		
	    $plgParams = $this->plgParams;
	    
	    //check category allow show comment
        if(!$this->checkShowComment($article)){
        	return '';
        }
            
	    if($plgParams->get('postion_add_button',0) == 0){	 		
	    	return $this->showButton($article);
	    }else{
			return '';
	    }        
	}
	
    //After_content_ads
	function onAfterDisplayContent( &$article, &$params, $limitstart ){
     	global $mainframe;     	
		if ( $mainframe->isAdmin() ) { return; }
		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php')){
			return '';	
		}
     	$option         = JRequest::getCmd('option');
		$print			= JRequest::getCmd('print', 0);		
		if($option != "com_content" || $print > 0){
			return '';    	
		}
     	
		$plgParams = $this->plgParams;
	    
		//check category allow show comment
        if(!$this->checkShowComment($article)){
        	return '';
        }
	    if($plgParams->get('postion_add_button',0) == 3){
	 		return $this->showButton($article);
	    }else{
			return '';
	    }
    }
	    
    //get text button
    function showButton($article){
    	global $mainframe;
    	if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php')){
			return '';	
		}
    	$plugin 	= $this->plugin;
    	$plgParams 	= $this->plgParams;
    	$content 	= "";
    	
    	require_once(JPATH_BASE.DS.'plugins'.DS.'content'.DS.$plugin->name.DS.'elements'.DS.'japaramhelper.php');
	    $element = new JElementJaparamhelper();	    
		$theme   = $element->getParamValue('layout', 'theme' , 'default');
		
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
	    
	    $id             = $article->id;
	    $option         = JRequest::getCmd('option');
        $view           = JRequest::getCmd('view');
        
        $links = $this->getLink($article);
         
        if($links != ""){	    
	    	ob_start ();
			require $element->getLinkButton("comments/getbutton.php");
			$content = ob_get_contents ();
			ob_end_clean ();
        }
        
		return $content;
    }
    
    //check allow show comment in a category
    function checkShowComment($article){
    	$option         = JRequest::getCmd('option');
		$print			= JRequest::getCmd('print', 0);
		
    	if($option != "com_content" || $print > 0 || !isset($article->sectionid)){
			return '';    	
		}
    	$sectionid      = $article->sectionid;
        $catid          = $article->catid;
        $id             = $article->id;
        $option         = JRequest::getCmd('option');
        $view           = JRequest::getCmd('view');
        $show           = JRequest::getCmd('show');
        $itemid         = JRequest::getInt('Itemid');                                
        $plgParams = $this->plgParams;    	
		
    	$check = true;
        if ($option != "com_content" && $option != "com_myblog"){ 
            $check = false;
        }   
        
        if(!$article->id) {
            $check = false;
        }
        
        if(preg_match($this->_plgCodeDisable, $article->text)) {
            $check = false;
        }
        
    	$catsid = $plgParams->get('catsid','');
        if (is_array($catsid)){
            $categories = $catsid;
        } else if ($catsid==''){
            $categories[] = $catid;
        } else {
            $categories[] = $catsid;
        }

        $menusid = $plgParams->get('menusid','');
        if (is_array($menusid)){
            $menus = $menusid;
        } else if ($menusid==''){
            $menus[] = $itemid;
        } else {
            $menus[] = $menusid;
        }
        
        if( !in_array($catid, $categories) || !in_array($itemid, $menus) ) {
			$check = false;
        }
            
     	return $check;
    }
    
    //get link of comment
    function getLink($article){    	
        $option         = JRequest::getCmd('option');
        $view           = JRequest::getCmd('view');
                
        $links = "";
        
    	if( $option=='com_content' && ($view=='frontpage' || $view=='section' || $view=='category')){                  
            $user = & JFactory::getUser();
            $aid = $user->get('aid');
            
            if ($article->access <= $user->get('aid', 0)){
                $links = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
            } else {
                $links = JRoute::_("index.php?option=com_user&task=register");
            }
        }
        return $links;	
    }       
    
	//Content_top_ads - Content_bottom_ads
    function onPrepareContent( &$article, &$params )
    {   
        if(file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php')){
            global $mainframe;           
			if ( $mainframe->isAdmin() ) { return; }			
            $option         = JRequest::getCmd('option');
        	$print			= JRequest::getCmd('print', 0);		
		
        	if($option != "com_content" || $print > 0 || !isset($article->sectionid)){
				return '';    	
			}
            $sectionid      = $article->sectionid;
            $catid          = $article->catid;
            $id             = $article->id;            
            $view           = JRequest::getCmd('view');
            $show           = JRequest::getCmd('show');
            $itemid         = JRequest::getInt('Itemid');                                      
            $conntentTitle	= $article->title;            

            //check category allow show comment
            if(!$this->checkShowComment($article)){
            	return '';		
            }
                       
            $links = $this->getLink($article);
            $plugin = $this->plugin;
            $plgParams = $this->plgParams;
                                                                                                                            
            //display button add comment
            if($links != ""){             
            	//Content top ads
            	if($plgParams->get('postion_add_button',0) == 1){
            		$buttonText = $this->showButton($article);
            		$article->text=$buttonText.$article->text; 	
            	}
            	//Content bottom ads
            	if($plgParams->get('postion_add_button',0) == 2){
            		$buttonText = $this->showButton($article);
            		$article->text.=$buttonText; 	
            	}            	 	 	                   
            }
            //display all comment
            else{
            	require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'helpers'.DS.'jahelper.php');
				$helper = new JACommentHelpers();
				
				$GLOBALS['jacconfig'] = array(); 
				JACommentHelpers::get_config_system();
				global $jacconfig;            					
            
            	$theme 				= $jacconfig['layout']->get('theme', 'default' );
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
            	
            	ob_start();            	 
				$session = &JFactory::getSession();
                // Put a value in a session var  
                $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
                $protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]),0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")).$s;
                $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
                $webUrl = $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];                                                                  
				
				$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
				$webUrl = str_replace($url,"",$webUrl);
				
                $session->set('commenturl', $webUrl);
                            	
            	//get css and JS befor perform ajax
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
            		//override for all component
            		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.comment.css')){
						JHTML::stylesheet('ja.comment.css', 'templates/'.$mainframe->getTemplate().'/css/');
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
											
				if(isset($jacconfig['general']) && $jacconfig['general']->get('is_comment_offline', 0)){
					if(!JACommentHelpers::check_access()) return ;
				}
									
				require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'helpers'.DS.'config.php');
				$lists['commenttype'] 	= 1;				
				$lists['contentoption'] = $option;
				$lists['contentid']   	= $id;
				$lists['jacomentUrl'] 	= $webUrl;
				$lists['contenttitle'] 	= $conntentTitle;							 																	
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
            	<script language="javascript" type="text/javascript">
            	//<![CDATA[
        		
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
	            	url = "<?php echo $url; ?>index.php?tmpl=component&option=com_jacomment&view=comments&contentoption=<?php echo $option;?>&contentid=<?php echo $id;?>&ran=" + Math.random();
	            	            	        
	            	if(id != 0){
						url += "&currentCommentID=" + id;
	            	}	
								            			           
					new Ajax(url, {method: 'get', onComplete: function(text){
						$('jac-wrapper').innerHTML = $('jac-wrapper').innerHTML + text;																		 						
						//$('jacommentid:'+id).getPosition();
						moveBackground(id, '<?php echo JURI::root();?>');																																						
						jac_auto_expand_textarea();																								
					}}).request();
						
				//]]>						
				</script>
            	<?php
            	$output = ob_get_contents();
	            ob_end_clean(); 
	            
	            $article->text .= $this->_plgStart.$output.$this->_plgEnd;             
            }
            
            
            // return for others comment system
            JRequest::setVar( 'option', 'com_content' );
            
            return true;
            
        }else{
            return false;   
        }
        
    }

}