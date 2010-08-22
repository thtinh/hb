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

defined( '_JEXEC' ) or die( 'Restricted access' ); 
global $jacconfig;
$theme = $jacconfig["layout"]->get("theme", "default");
$theme	 = JRequest::getVar("jacomment_theme", $theme);
//get css and JS befor perform ajax
if(!defined('JACOMMENT_GLOBAL_CSS')){
	global $mainframe;
	//add style for japopup			      
	JHTML::stylesheet('ja.popup.css', 'components/com_jacomment/asset/css/');
	//override template for japopup in template
    if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.popup.css')){
	    JHTML::stylesheet('ja.popup.css', 'templates/'.$mainframe->getTemplate().'/css/');
	}

	//add style for all componennt
	JHTML::stylesheet('ja.comment.css', 'components/com_jacomment/asset/css/');
	//override for all component
        		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.comment.css')){
		JHTML::stylesheet('ja.comment.css', 'templates/'.$mainframe->getTemplate().'/css/');
	}
	
	//add style only IE for all component
	JHTML::stylesheet('ja.ie.php', 'components/com_jacomment/asset/css/');            							
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
								
		JHTML::stylesheet('ja.comment_rtl.css', 'components/com_jacomment/asset/css/');																		
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
	
        		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/ja.comment.css')){
		JHTML::stylesheet('ja.comment.css', 'templates/'.$mainframe->getTemplate().'/css/');
	}
	
	define('JACOMMENT_GLOBAL_CSS', true);
}
if(!defined('JACOMMENT_GLOBAL_JS')){
	JHTML::script('jquery-1.3.1.js', 'components/com_jacomment/asset/js/');
    JHTML::script('ja.comment.js', 'components/com_jacomment/asset/js/');
    JHTML::script('ja.popup.js', 'components/com_jacomment/asset/js/');  
    define('JACOMMENT_GLOBAL_JS', true);
}
?>
<div id="frm_login" class="clearfix<?php if(JPluginHelper::isEnabled('system', 'plg_jarpxnow') && $jacconfig['layout']->get('enable_login_rpx')){ ?> jac-two-login<?php }?>" >
	
    <div id="jac-login-joomla-form">
	    <form action="index.php" method="post" name="JAFrom" id="JAFrom" >
	    	<h2 style="font-size: 116%; margin-top: 0pt;"><?php echo JText::_('Login for Registered Users')?></h2>
	        <p id="ja-form-login-username" class="clearfix">
	          <label for="username"><?php echo JText::_('Username') ?></label>
	          <input name="username" id="username" type="text" class="txtbox" alt="username" size="26" />
	        </p>
	        <p id="ja-form-login-password" class="clearfix">
	          <label for="passwd"><?php echo JText::_('Password') ?></label>
	          <input type="password" name="passwd" id="passwd" class="txtbox" size="26" alt="password" />
	        </p>
	        <input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
	        <ul>
	            <li>
	                <a href="<?php echo JRoute::_( 'index.php?option=com_user&amp;view=reset' ); ?>">
	                <?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
	            </li>
	            <li>
	                <a href="<?php echo JRoute::_( 'index.php?option=com_user&amp;view=remind' ); ?>">
	                <?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
	            </li>
	            <?php
	            $usersConfig = &JComponentHelper::getParams( 'com_users' );
	            if ($usersConfig->get('allowUserRegistration')) : ?>
	            <li>
	                <a href="<?php echo JRoute::_( 'index.php?option=com_user&amp;task=register' ); ?>">
	                    <?php echo JText::_('REGISTER'); ?></a>
	            </li>
	            <?php endif; ?>
	        </ul>
			
	        <input type="hidden" name="option" value="com_jacomment" />
	        <input type="hidden" name="view" value="users" />
	        <input type="hidden" name="task" value="signin" />
	        <input type="hidden" name="tmpl" value="component" />
	        <?php if(JRequest::getInt("createlink",0) == 1){
	        	$session = &JFactory::getSession();
	        	$session->set("returnLink", $_SERVER['HTTP_REFERER']);	        		
	        }?>
            <input type="hidden" name="return" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
	        <?php echo JHTML::_( 'form.token' ); ?>
	    </form>
    <?php //echo $this->frmLogin;?>
    </div>
    
    
	<?php if(JPluginHelper::isEnabled('system', 'plg_jarpxnow') && $jacconfig['layout']->get('enable_login_rpx')){ ?>
	<div id="jac-login-rpx">
	    <iframe src="https://<?php echo $this->application;?>/openid/embed?token_url=<?php echo $this->token_url;?>" scrolling="no" frameBorder="no" style="width:350px;height:240px;"></iframe>
	</div>                                                     
	<?php } ?>
</div>