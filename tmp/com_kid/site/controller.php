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
jimport('joomla.html.pagination');
/**
 * Staff Component Controller
 *
 * @package		Staff
 */
class StaffController extends JController {

    /**
     * Method to display the view
     *
     * @access	public
     */
    function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask( 'display_detail','display_detail');
        $this->registerTask( 'search','search');
        $this->registerTask( 'staff','display_staffs');
        $this->registerTask('staff_by','display_staffs_by');
    }
    function search() {
        $model = $this->getModel('Search');
        $searchtype = $model->getSearchType();

        $view = null;
        if ($searchtype=='name'||$searchtype=='email')
            $view = & $this->getView( 'Staff', 'html' );
        else $view = & $this->getView('Division','html');
        global $mainframe;
        $app =& JFactory::getApplication();
        $pathway =& $app->getPathway(); // get the pathway object we want to modify
        /*manually add breadcrumbs, to delete if duplicate*/
        $pathway->addItem('Staff Directory','index.php?option=com_staff&view=division');
        $pathway->addItem('Search');
        $view->setModel( $model, true );
        $view->displayResult($tpl);
    }
    function display_staffs() {
        $model = $this->getModel('Staff');
        $total = $model->getTotal();
        $view = & $this->getView( 'Staff', 'html' );

        $view->setModel( $model, true );
        $view->display($tpl);
    }
    function display_staffs_by() {

        $division_id = JRequest::getVar( 'di_id', 0, '', 'int' );
        $department_id = JRequest::getVar( 'de_id', 0, '', 'int' );
        $model = $this->getModel('Staff');
        $divisionModel = $this->getModel('Division');

        $view = & $this->getView( 'Staff', 'html' );
        $view->setModel( $model, true );

        $division = $divisionModel->getDivision($division_id);
        $department = $divisionModel->getDivision($department_id);

        global $mainframe;
        $app =& JFactory::getApplication();
        $pathway =& $app->getPathway(); // get the pathway object we want to modify
        /*manually add breadcrumbs, to delete if duplicate*/
        if ($division) {
            if ($department) {
                $pathway->addItem('Staff Directory','index.php?option=com_staff&view=division');
                $pathway->addItem($division->name,'index.php?option=com_staff&task=staff_by&di_id='.$division->id);
                $pathway->addItem($department->name);
            }else {
                $pathway->addItem('Staff Directory','index.php?option=com_staff&view=division');
                $pathway->addItem($division->name);
            }
        }
        $view->setModel($divisionModel);
        $view->displayByDivision();
    }
    function display() {
        $model = $this->getModel('Division');
        $view = & $this->getView( 'Division', 'html' );
        $view->setModel( $model, true );

        global $mainframe;
        $app =& JFactory::getApplication();
        $pathway =& $app->getPathway(); // get the pathway object we want to modify
        /*manually add breadcrumbs, to delete if duplicate*/
        $pathway->addItem('Staff Directory','index.php?option=com_staff&view=division');
        //default view
        $view->display();
    }
    function display_detail() {
        $model = $this->getModel('Staff');
        $view = & $this->getView( 'Staff', 'html' );
        $view->setModel( $model, true );
        $view->setLayout('form');
        $view->displayDetail();
    }

}
?>
