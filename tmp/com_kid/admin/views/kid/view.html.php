<?php
/**
 * Kid View for Kid Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Kid View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class KidViewKid extends JView {
    /**
     * display method of Kid view
     * @return void
     **/
    function display($tpl = null) {
        //get the Kid
        $Kid		=& $this->get('Data');
        $isNew		= ($Kid->id < 1);
        if ($Kid->created == '' || $isNew) {
            $Kid->created = date('Y-m-d');
        }

        $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
        JToolBarHelper::title(   JText::_( 'Kid' ).': <small><small>[ ' . $text.' ]</small></small>' );
        JToolBarHelper::save();
        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            // for existing items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', 'Close' );
        }

        $editor = &JFactory::getEditor();
        $this->assignRef('Kid',$Kid);
        $this->assign('editor',$editor);
        parent::display($tpl);
    }
}