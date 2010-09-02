<?php
/**
 * Kid Model for Kid Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Kid Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class KidModelKid extends JModel {
    /**
     * Constructor that retrieves the ID from the request
     *
     * @access	public
     * @return	void
     */
    function __construct() {
        parent::__construct();

        $array = JRequest::getVar('cid',  0, '', 'array');
        $this->setId((int)$array[0]);
    }

    /**
     * Method to set the Kid identifier
     *
     * @access	public
     * @param	int Kid identifier
     * @return	void
     */
    function setId($id) {
        // Set id and wipe data
        $this->_id		= $id;
        $this->_data	= null;
    }

    /**
     * Method to get a Kid
     * @return object with data
     */
    function &getData() {
        // Load the data
        if (empty( $this->_data )) {
            $query = ' SELECT * FROM #__kid '.
                    '  WHERE id = '.$this->_id;
            $this->_db->setQuery( $query );
            $this->_data = $this->_db->loadObject();
        }
        if (!$this->_data) {
            $this->_data = new stdClass();
            $this->_data->id = 0;
            $this->_data->name = null;
        }
        return $this->_data;
    }

    /**
     * Method to store a record
     *
     * @access	public
     * @return	boolean	True on success
     */
    function store() {
        $row =& $this->getTable();
        
        $data = JRequest::get( 'post' );

        if (trim($data['name']) == '') {
            $this->setError('Name cannot be blank');
            return false;
        }
        if (trim($data['dob']) == '') {
            $this->setError('Date of Birth cannot be blank');
            return false;
        }
        
        $row->avatar = $data['imageurl'];

        error_log(print_r($data, true));

        
        // Bind the form fields to the Kid table
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrors());
            return false;
        }

        // Make sure the Kid record is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrors());
            return false;
        }

        // Store the web link table to the database
        if (!$row->store()) {
            $this->setError($row->getErrors() );
            return false;
        }
      
        return true;
    }

    /**
     * Method to delete record(s)
     *
     * @access	public
     * @return	boolean	True on success
     */
    function delete() {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

        $row =& $this->getTable();

        if (count( $cids )) {
            foreach($cids as $cid) {
                if (!$row->delete( $cid )) {
                    $this->setError( $row->getError() );
                    return false;
                }
            }
        }
        return true;
    }

}