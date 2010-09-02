<?php
/**
 * Kids Model for Kid Component
 *
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.html.pagination');
/**
 * Kid Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class KidModelKids extends JModel {
    /**
     * Kids data array
     *
     * @var array
     */
    var $_data;
    var $_filter_name;
    function __construct() {
        parent::__construct();

        global $mainframe, $option;
        // Get pagination request variables
        $limit = 15;
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $search	= JRequest::getVar( 'search', '', '', 'string' );
        $search = JString::strtolower($search);
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $this->setFilterName($search);
        $this->setState('search',$search);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }
    function getLimitstart(){
        return $this->getState('limitstart');
    }
     function getTotal() {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }
    function getSearchWord(){
        return $this->getState('search');
    }
    function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }
    function setFilterName($filter = null) {
        $this->_filter_name = $filter;
    }


    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery() {
       
        $query = 'SELECT * FROM #__Kid k';
        if ($this->_filter_name)
             $query="SELECT * FROM #__Kid k where k.name LIKE '%$this->_filter_name%'";
        //this should sort vietnamese's name correctly
        $order = " order by k.name COLLATE utf8_unicode_ci";
        $query .= $order;
        return $query;
    }
    function resetFilter(){
 
        $this->_data = null;
    }

    /**
     * Retrieves the Kid data
     * @return array Array of objects containing the data from the database
     */
    function getData() {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data )) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        //$this->resetFilter();
        return $this->_data;
    }
}