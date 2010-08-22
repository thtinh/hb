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


defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

class JACommentController extends JController {
	function display() {		
		global $jacconfig;
		$view = JRequest::getVar('view');
		$layout = JRequest::getVar('layout');			
		$showLicenseWarmningMsg = ($view == 'comment' && $layout == 'license') ? true : false;	
		$objVerify = new JACommentLicense();
		$objVerify->updateFail();
		
		//check is valid license key
		if(!isset($_SESSION['JACOMMENT_VERIFY_PASSED'])) {			
			//if don't register new license			
			if(!isset($jacconfig) || !isset($jacconfig["license"]) || $jacconfig["license"]->get("email") == "" || $jacconfig["license"]->get("payment_id") == ""){					
				if($showLicenseWarmningMsg){					
					JError::raiseWarning(0, JText::_('Please verify your license.'));									
				}
			}
			//already register license
			else{
				$last_verify = strtotime($jacconfig["license"]->get("last_verify",0));				
				$next_verify = $last_verify + 5 * 86400;
				
				if ($jacconfig["license"]->get("verify_is_passed",0) != 1) {
					if($showLicenseWarmningMsg)
						JError::raiseWarning(0, JText::_('Your License Key could not be verified.'));
				} elseif($next_verify < time()) {
					//$this->setRedirect('index.php?option='.JACOMPONENT.'&view=license&task=verify');
										
					JRequest::setVar('email', $jacconfig["license"]->get("email"), 'post');
					JRequest::setVar('payment_id', $jacconfig["license"]->get("payment_id"), 'post');
					$objVerify = new JACommentLicense();
					$objVerify->verify_license();
				} else {
					$_SESSION['JACOMMENT_VERIFY_PASSED'] = 1;
				}
				
			}
		}
		parent::display();	    
	}
}
?>