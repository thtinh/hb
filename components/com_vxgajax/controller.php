<?php
/**
 * @version		$Id: controller.php 14974 2010-02-21 14:32:22Z ian $
 * @package		Joomla
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * Contact Component Controller
 *
 * @static
 * @package		Joomla
 * @subpackage	Contact
 * @since 1.5
 */
class AjaxController extends JController {

    function getSponsorList() {
        require_once(JPATH_COMPONENT.DS.'sponsorshelper.php');       
        return array();
    }
    function display() {
        $result = null;
        switch ($this->getTask()) {
            //index.php?option=com_contact&task=category&id=0&Itemid=4
            case 'SponsorList':
                $result = $this->getSponsorList();
                break;
            case 'ToBeAdvised':
                break;
        }
        echo $result;
    }
    /**
     * Validates some inputs based on component configuration
     *
     * @param Object	$contact	JTable Object
     * @param String	$email		Email address
     * @param String	$subject	Email subject
     * @param String	$body		Email body
     * @return Boolean
     * @access protected
     * @since 1.5
     */
    function _validateInputs( $contact, $email, $subject, $body ) {
        global $mainframe;

        $session =& JFactory::getSession();

        // Get params and component configurations
        $params		= new JParameter($contact->params);
        $pparams	= &$mainframe->getParams('com_contact');

        // check for session cookie
        $sessionCheck 	= $pparams->get( 'validate_session', 1 );
        $sessionName	= $session->getName();
        if  ( $sessionCheck ) {
            if ( !isset($_COOKIE[$sessionName]) ) {
                $this->setError( JText::_('ALERTNOTAUTH') );
                return false;
            }
        }

        // Determine banned e-mails
        $configEmail	= $pparams->get( 'banned_email', '' );
        $paramsEmail	= $params->get( 'banned_mail', '' );
        $bannedEmail 	= $configEmail . ($paramsEmail ? ';'.$paramsEmail : '');

        // Prevent form submission if one of the banned text is discovered in the email field
        if(false === $this->_checkText($email, $bannedEmail )) {
            $this->setError( JText::sprintf('MESGHASBANNEDTEXT', JText::_('Email')) );
            return false;
        }

        // Determine banned subjects
        $configSubject	= $pparams->get( 'banned_subject', '' );
        $paramsSubject	= $params->get( 'banned_subject', '' );
        $bannedSubject 	= $configSubject . ( $paramsSubject ? ';'.$paramsSubject : '');

        // Prevent form submission if one of the banned text is discovered in the subject field
        if(false === $this->_checkText($subject, $bannedSubject)) {
            $this->setError( JText::sprintf('MESGHASBANNEDTEXT',JText::_('Subject')) );
            return false;
        }

        // Determine banned Text
        $configText		= $pparams->get( 'banned_text', '' );
        $paramsText		= $params->get( 'banned_text', '' );
        $bannedText 	= $configText . ( $paramsText ? ';'.$paramsText : '' );

        // Prevent form submission if one of the banned text is discovered in the text field
        if(false === $this->_checkText( $body, $bannedText )) {
            $this->setError( JText::sprintf('MESGHASBANNEDTEXT', JText::_('Message')) );
            return false;
        }

        // test to ensure that only one email address is entered
        $check = explode( '@', $email );
        if ( strpos( $email, ';' ) || strpos( $email, ',' ) || strpos( $email, ' ' ) || count( $check ) > 2 ) {
            $this->setError( JText::_( 'You cannot enter more than one email address', true ) );
            return false;
        }

        return true;
    }

    /**
     * Checks $text for values contained in the array $array, and sets error message if true...
     *
     * @param String	$text		Text to search against
     * @param String	$list		semicolon (;) seperated list of banned values
     * @return Boolean
     * @access protected
     * @since 1.5.4
     */
    function _checkText($text, $list) {
        if(empty($list) || empty($text)) return true;
        $array = explode(';', $list);
        foreach ($array as $value) {
            $value = trim($value);
            if(empty($value)) continue;
            if ( JString::stristr($text, $value) !== false ) {
                return false;
            }
        }
        return true;
    }
}
