<?php
/**
 * Staff View for Staff Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport( 'joomla.application.component.view');
jimport('joomla.html.pagination');
/**
 * HTML View class for the Staff Component
 *
 * @package		Joomla.Tutorials
 * @subpackage	Components
 */
class StaffViewStaff extends JView {

    function display($tpl = null, $pagination = null) {
        $data = $this->get( 'Staffs' );
        $pagination = $this->get('Pagination');
        $this->assign( 'data',	$data );
        $this->assign('pageNav',$pagination);
        $lists['order_Dir'] = $state->get( 'filter_order_Dir' );
        $lists['order']     = $state->get( 'filter_order' );
        $this->assignRef( 'lists', $lists );
        parent::display($tpl);
    }
    function displayByDivision() {
        $di_id = JRequest::getVar( 'di_id', 0, '', 'int' );
        $de_id = JRequest::getVar( 'de_id', 0, '', 'int' );
        /* Call the state object */
        $state =& $this->get( 'state' );
        /* Get the values from the state object that were inserted in the model's construct function */
        $lists['order_Dir'] = $state->get( 'filter_order_Dir' );
        $lists['order']     = $state->get( 'filter_order' );

        //get data from models
        $data = $this->get('StaffsByDivision');
        $pagination = $this->get('Pagination');
        $divisionModel = $this->getModel('Division');      
        $division = $divisionModel->getDivision($di_id);
        $department = $divisionModel->getDivision($de_id);
        $departmentlist = $divisionModel->getDepartments($di_id);
        
        //assign data to the view
        $this->assign( 'data',	$data );
        $this->assign('pageNav',$pagination);
        $this->assign('di_id',$di_id);
        $this->assign('de_id',$de_id);
        $this->assign('task','staff_by');
        $this->assign('division',$division);
        $this->assign('department',$department);
        $this->assign('departmentlist',$departmentlist);
        $this->assignRef( 'lists', $lists );

        parent::display($tpl);
    }
    function displayDetail($tpl = null) {
        $data = $this->get( 'Staff' );
        $department = $this->get('StaffDepartment');
        $division = $this->get('StaffDivision');
        $this->assign( 'data',	$data );
        $this->assign( 'department',	$department );
        $this->assign( 'division',	$division );
        parent::display($tpl);
    }
    function displayResult($tpl = null, $pagination = null) {
        $data = $this->get( 'Data' );

        if(count($data) > 0) {
            
            /* Call the state object */
            $state =& $this->get( 'state' );
            $lists['order_Dir'] = $state->get( 'filter_order_Dir' );
            $lists['order']     = $state->get( 'filter_order' );

            //get data from models
            $pagination = $this->get('Pagination');
            
            //assign data to the view
            $this->assign( 'data',	$data );
            $this->assign('task','search');
            $this->assignRef( 'lists', $lists );
            $this->assign('pageNav',$pagination);
            $this->assign('searchword',$this->get('SearchWord'));
            $this->assign('searchtype',$this->get('SearchType'));

            parent::display('result');
        }
        else parent::display('noresult');
    }
}
?>
