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
jimport('joomla.html.pagination');
/**
 * Kid Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class KidModelKid extends JModel {

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
        $query = "select k.* from #__kid k";
        return $query;
    }
    function _buildContentOrderBy() {
        global $mainframe, $option;

        $orderby = '';
        $filter_order     = $this->getState('filter_order');
        $filter_order_Dir = $this->getState('filter_order_Dir');
       
        /* Error handling is never a bad thing*/
        //this is to ensure vietnamese's name will be sorted correctly
        if(!empty($filter_order) && !empty($filter_order_Dir) ) {
            $orderby = ' ORDER BY k.'.$filter_order.' COLLATE utf8_unicode_ci '.$filter_order_Dir;
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
     * Gets the Kid
     * @return string The Kid to be displayed to the user
     */
    function getKids() {
        $db =& JFactory::getDBO();
        $query = $this->_buildQuery(). $this->_buildContentOrderBy();
        $this->total = $this->_getListCount($query);

        $db->setQuery($query,$this->limitstart,$this->limit);
        $data = $db->loadObjectList();

        return $data;
    }

    function getKid() {
        $cid = JRequest::getVar( 'cid', 0, '', 'int' );
        $db =& JFactory::getDBO();
        $query = 'SELECT * FROM #__Kid where id='.$cid;
        $db->setQuery($query);
        $data = $db->loadObject();
        return $data;
    }
    
   

}
