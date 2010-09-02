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
    
    function display($tpl = null) {
        $view = & $this->getView('Kids', 'html');
        $model = $this->getModel('Kids');

        $view->setModel($model, true);

        $view->display($tpl);
    }


}