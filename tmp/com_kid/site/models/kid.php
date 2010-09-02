<?php
/**
 * Staff Model for Staff Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
jimport('joomla.html.pagination');
/**
 * Staff Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class StaffModelStaff extends JModel {

    var $_total = null;
    var $_pagination = null;
    var $_data = null;

    function __construct() {
        parent::__construct();

        global $mainframe, $option;
        // Get pagination request variables
        $limit = 15;
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $filter_order     = $mainframe->getUserStateFromRequest(  $option.'filter_order', 'filter_order', 'name', 'cmd' );
        $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );

        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);

    }
    function getLimitstart(){
        return $this->getState('limitstart');
    }
    function _buildQuery() {
        $di_id = JRequest::getVar( 'di_id', 0, '', 'int' );
        $de_id = JRequest::getVar( 'de_id', 0, '', 'int' );

        if ($di_id != 0 and $de_id == 0) {
            $query = 'select distinct s.* from (#__staff s,#__division_staffs ds1,#__division_staffs ds2) where s.id = ds1.staff_id and s.id = ds2.staff_id '.
                    'and ds1.division_id='.$di_id;
        }
        else if ($de_id != 0) {
            $query = 'select distinct s.* from (#__staff s,#__division_staffs ds1,#__division_staffs ds2) where s.id = ds1.staff_id and s.id = ds2.staff_id '.
                    'and ds1.division_id='.$di_id.' and ds2.division_id='.$de_id;
        }
        return $query;
    }
    function _buildContentOrderBy() {
        global $mainframe, $option;

        $orderby = '';
        $filter_order     = $this->getState('filter_order');
        $filter_order_Dir = $this->getState('filter_order_Dir');

        /* Error handling is never a bad thing*/
        if(!empty($filter_order) && !empty($filter_order_Dir) ) {
            $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
        }

        return $orderby;
    }
    
    function getTotal() {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;

    }

    function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }
    /**
     * Gets the Staff
     * @return string The Staff to be displayed to the user
     */
    function getStaffs() {
        $db =& JFactory::getDBO();
        $query = 'SELECT * FROM #__staff';
        $this->total = $this->_getListCount($query);
        $db->setQuery($query,$this->limitstart,$this->limit);
        $data = $db->loadObjectList();

        return $data;
    }

    function getStaff() {
        $cid = JRequest::getVar( 'cid', 0, '', 'int' );
        $db =& JFactory::getDBO();
        $query = 'SELECT * FROM #__staff where id='.$cid;
        $db->setQuery($query);
        $data = $db->loadObject();
        return $data;
    }
    function getStaffDepartment() {
        $cid = JRequest::getVar( 'cid', 0, '', 'int' );

        $db =& JFactory::getDBO();
        $query = 'select distinct d.name from #__division d, #__division_staffs ds, #__division_map m
where d.id = ds.division_id and d.parent = 0 and ds.staff_id ='.$cid;
        $db->setQuery($query);
        $data = $db->loadResult();
        return $data;
    }
    function getStaffDivision() {
        $cid = JRequest::getVar( 'cid', 0, '', 'int' );
        $db =& JFactory::getDBO();
        $query = 'select distinct d.name from #__division d, #__division_staffs ds, #__division_map m
where d.id = ds.division_id and d.parent = 1 and ds.staff_id ='.$cid;
        $db->setQuery($query);
        $data = $db->loadResult();
        return $data;
    }

    function getStaffsByDivision() {
        // if data hasn't already been obtained, load it
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $query .= $this->_buildContentOrderBy();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }
}
