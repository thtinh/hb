<?php

/**
 * Staff default controller
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */
jimport('joomla.application.component.controller');

/**
 * Staff Component Controller
 *
 * @package		Staff
 */
class KidController extends JController {

    /**
     * Method to display the view
     *
     * @access	public
     */
    function display() {
        //JRequest::setVar('view', 'Kids');
        parent::display();
    }

}

?>
