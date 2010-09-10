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
    var $_total = null;
    var $_pagination = null;
    var $_data = null;
    var $_filterillness = null;
    var $_filteryear = null;
    function __construct() {
        parent::__construct();

        global $mainframe, $option; 
        // Get pagination request variables
        $limit = 5;
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
  
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        
        
        
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);       
        
        $this->_filterillness     = $mainframe->getUserStateFromRequest(  $option.'illness', 'illness', 'name', 'string' );
        $this->_filteryear = $mainframe->getUserStateFromRequest(  $option.'year', 'year', 'name', 'string' );
        
        if ($this->getState('year')==null){
            $this->setState('year', $this->_filteryear);
        }
         if ($this->getState('illness')==null){
            $this->setState('illness', $this->_filterillness);
        }
     
        

    }
    function getLimit() {
        return $this->getState('limit');
    }
    function getLimitstart() {
        return $this->getState('limitstart');
    }
    function getIllness() {
        return $this->getState('illness');
    }
    function getYear() {
        return $this->getState('year');
    }
    function _buildQuery() {
        $query="SELECT * FROM #__Kid k ".$this->_buildWhere();
    
        return $query;
    }
    function _buildWhere() {
        $where = "where ";
        $illness = $this->getIllness();
        $year = $this->getYear();
        if ($illness!="" && $year!="") {
            $where .="k.illness LIKE '%$illness%' AND k.dob LIKE '%$year%'";
            return $where;
        }
        else {
            if ($illness) {
                $where .="k.illness LIKE '%$illness%'";
                return $where;
            }
            if ($year) {
                $where .="k.dob LIKE '%$year%'";
                return $where;
            }
        }


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
     * Gets the Kid
     * @return string The Kid to be displayed to the user
     */
    function getKids() {
        $db =& JFactory::getDBO();
        $query = $this->_buildQuery();
        
        $this->total = $this->_getListCount($query);

        $db->setQuery($query,$this->getLimitstart(),$this->getLimit());
        $data = $db->loadObjectList();

        return $data;
    }

}
