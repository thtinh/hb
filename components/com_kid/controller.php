<?php

/**
 * Kid default controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */
jimport('joomla.application.component.controller');
jimport('joomla.html.pagination');

/**
 * Kid Component Controller
 *
 * @package		Kid
 */
class KidController extends JController {

    /**
     * Method to display the view
     *
     * @access	public
     */
    function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask('display_detail', 'display_detail');
        $this->registerTask('search', 'search');
        $this->registerTask('display_json', 'display_json');
    }

    function search() {
         $model = $this->getModel('Search');
         $view = & $this->getView('Kid', 'html');
         $view->setModel($model, true);
         $view->display();
    }

    function display_Kids() {

    }

    function display_Kids_by() {

    }

    function display() {
        $model = $this->getModel('Kid');
        $view = & $this->getView('Kid', 'html');
        $view->setModel($model, true);

        global $mainframe; //wtf ????
        $app = & JFactory::getApplication();
        $pathway = & $app->getPathway(); // get the pathway object we want to modify
        /* manually add breadcrumbs, to delete if duplicate */
        //$pathway->addItem('Kid Directory', 'index.php?option=com_kid&view=kid');
        //default view
        $view->display();
    }

    function display_detail() {
        $model = $this->getModel('Kid');
        $view = & $this->getView('Kid', 'html');
        $view->setModel($model, true);
        $view->setLayout('detail');
        global $mainframe; //wtf ????
        $app = & JFactory::getApplication();
        $pathway = & $app->getPathway(); // get the pathway object we want to modify
        /* manually add breadcrumbs, to delete if duplicate */
        $pathway->addItem('Chi tiết', 'index.php?option=com_kid&view=kid');
        $view->displayDetail();
    }

    function display_json() {
        $model = $this->getModel('Kid');
        $view = & $this->getView('Kid', 'json');
        $view->setModel($model, true);
        $view->display();
    }
}

?>
