<?php
/**
 * Kid Model for Kid Component
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
 * Kid Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class KidModelSearch extends JModel {
    /**
     * Gets the Kid
     * @return string The Kid to be displayed to the user
     */

    var $_pagination = null;

    var $limit, $limitstart;
    var $_data = null;
    var $_total = null;
    var $_searchAreas = null;
    var $_searchword = null;
    var $_searchType = null;
    
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
        $limit = 5;
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $this->_searchType = JRequest::getWord('searchtype','name');
        $this->_searchword = urldecode(JRequest::getString('searchword'));

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
   
    function _buildQuery(){
        $searchword = $this->_searchword;
        switch ($this->_searchType){
            case "year" : return "select distinct s.* from #__Kid s, #__division_Kids ds, #__division d
                where s.id = ds.Kid_id and d.id = ds.division_id and s.name like '%$searchword%'";break;
            case "illness": return "select distinct s.* from #__Kid s, #__division_Kids ds, #__division d
                where s.id = ds.Kid_id and d.id = ds.division_id and s.email like '%$searchword%'";break;
            
            default : return "";
        }
    }
 
    function getData() {
        // Lets load the content if it doesn't already exist      
       
        if (empty($this->_data)&&trim($this->_searchword)!='') {
            $db =& JFactory::getDBO();
            $query = $this->_buildQuery();
            
            //echo $query;
            $db->setQuery($query,$this->limitstart,$this->limit);
            //$this->_data = $db->loadObjectList();

            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

}
