<?php

/**
 * Kid Controller for Kid Component
 *
 * @package    	Joomla.Tutorials
 * @subpackage 	Components
 * @license		Commercial
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Kid Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class KidControllerKid extends KidController {

    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask('add', 'edit');
    }

    function display($tpl = null) {
        $view = & $this->getView('Kids', 'html');
        $model = $this->getModel('Kids');

        $view->setModel($model, true);

        $view->display($tpl);
    }

    /**
     * display the edit form
     * @return void
     */
    function edit() {
        $view = & $this->getView('Kid', 'html');
        $model = $this->getModel('Kid');

        $view->setModel($model, true);
        $view->setLayout('form');
        $view->display($tpl);
    }

    /**
     * save a record (and redirect to main page)
     * @return void
     */
    function save() {
        $model = $this->getModel('Kid');

        if ($model->store($post)) {
            $msg = JText::_('Kid Saved');
        } else {
            $errors = $model->getErrors();
            $errorMessages = "";
            foreach ($errors as $error) {
                $errorMessages .= $error . '<br/>';
            }
            $msg = JText::_('Error Saving Kid ! Reason: ' . $errorMessages);
        }

        // Check the table in so it can be edited.... we are done with it anyway
        $link = 'index.php?option=com_kid';
        $this->setRedirect($link, $msg);
    }

    /**
     * remove record(s)
     * @return void
     */
    function remove() {
        $model = $this->getModel('Kid');
        if (!$model->delete()) {
            $msg = JText::_('Error: One or More Kid could not be Deleted');
        } else {
            $msg = JText::_('Kid(s) Deleted');
        }

        $this->setRedirect('index.php?option=com_kid', $msg);
    }

    /**
     * cancel editing a record
     * @return void
     */
    function cancel() {
        $msg = JText::_('Operation Cancelled');
        $this->setRedirect('index.php?option=com_kid', $msg);
    }

}