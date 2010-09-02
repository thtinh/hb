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

/**
 * Staff Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class StaffModelSearch extends JModel {
    /**
     * Gets the Staff
     * @return string The Staff to be displayed to the user
     */

    var $_pagination = null;

    var $limit, $limitstart;
    var $_data = null;
    var $_total = null;
    var $_searchAreas = null;
    var $_searchword = null;
    var $_searchType = null;
    var $_filter_division = null;
    
    function getSearchWord(){
        return $this->_searchword;
    }
    function getSearchType() {
        return $this->_searchType;
    }
    function __construct(){
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

        $this->_searchType = JRequest::getWord('searchtype','name');
        $this->_searchword = urldecode(JRequest::getString('searchword'));
        $this->_filter_division = JRequest::getVar( 'division_id', 0, '', 'int' );
        
        //$this->getData();
    }
    function getTotal() {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $query .= $this->_buildDivisionFilter();
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
    function _buildDivisionFilter(){

        $filter = '';

        if ($this->_filter_division > 0 and ($this->_searchType == 'name'||$this->_searchType=='email'))
                $filter = ' and ds.division_id='.$this->_filter_division;
        return $filter;
    }
    function _buildQuery(){
        $searchword = $this->_searchword;
        switch ($this->_searchType){
            case "name" : return "select distinct s.* from #__staff s, #__division_staffs ds, #__division d
                where s.id = ds.staff_id and d.id = ds.division_id and s.name like '%$searchword%'";break;
            case "email": return "select distinct s.* from #__staff s, #__division_staffs ds, #__division d
                where s.id = ds.staff_id and d.id = ds.division_id and s.email like '%$searchword%'";break;
            case "division" : return "select distinct d.* from #__staff s, #__division_staffs ds, #__division d
where s.id = ds.staff_id and d.id = ds.division_id and d.name like '%$searchword%'";break;
            default : return "";
        }
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
    function getData() {
        // Lets load the content if it doesn't already exist      
       
        if (empty($this->_data)&&trim($this->_searchword)!='') {
            $db =& JFactory::getDBO();
            $query = $this->_buildQuery();
            $query .= $this->_buildDivisionFilter();
            $query .= $this->_buildContentOrderBy();
            //echo $query;
            $db->setQuery($query,$this->limitstart,$this->limit);
            //$this->_data = $db->loadObjectList();

            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

}
