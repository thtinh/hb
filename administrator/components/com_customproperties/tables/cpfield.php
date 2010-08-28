<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.5 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Customproperties Fields Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableCpfield extends JTable
{
  /* record id */
  var $id = 0;
  /* field name */
  var $name = null;
  /* field label */
  var $label = null ;
  /* filed type (checkbox or list) */
  var $field_type = null;
  /* parent module(s) : comma separated list of modules featuring this field*/
  var $modules = null;
  /* state (published , unpublished)*/
  var $published = null;
  /* access (public, registered ..)*/
  var $access = null;
  /* ordering */
  var $ordering = null;

  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function TableCpfield( &$db ) {
      parent::__construct('#__custom_properties_fields', 'id', $db);
  }

  /**
   * Binds an array to the object
   * @param 	array	Named array
   * @param 	string	Space separated list of fields not to bind
   * @return	boolean
   */
  function bind( $array, $ignore='' )
  {
    $result = parent::bind( $array );
    // cast properties
    $this->id	= (int) $this->id;

    return $result;
  }
  /**
   * Overloaded check function
   * @return boolean true if check is positive, false otherwise
   */
	function check(){
		$allowed_field_type = array('select','checkbox','text');
		// clean module list
		$this->modules = preg_replace( '/[^0-9\,]/', '', $this->modules );
		/* validity check */
		if( !$this->_isInt($this->id) || $this->id < 0){
			$this->setError(JText::_("Wrong ID"));
			return false;
		}
		if( !$this->_isInt($this->access) || $this->access < 0){
			$this->setError(JText::_("Wrong access"));
			return false;
		}
		if($this->name == '' || $this->name == null){
			$this->setError(JText::_("WRONG NAME"));
			return false;
		}
		if($this->label == '' || $this->label == null){
			$this->setError(JText::_("WRONG LABEL"));
			return false;
		}
		if(! in_array($this->field_type, $allowed_field_type) ){
			$this->setError(JText::_("WRONG TYPE")) ;
			return false;
		}
		if(strlen($this->modules) > 255){
			$this->setError(JText::_("TOO MANY MODULES"));
			return false;
		}
		return true;
	}

  /**
   * Overloaded delete function. Deletes the field AND its values AND links
   * @return boolean true if check is positive, false otherwise
   */
	function delete(){
		// delete field AND its values AND records from customProperties
		$query = "DELETE FROM #__custom_properties_fields
			WHERE id = '" . $this->id . "'" ;
		$this->_db->setQuery($query);
		if($this->_db->query() === false) return false ;

		// exterminate orphans
		$query = "DELETE FROM #__custom_properties_values
			WHERE field_id = '" . $this->id . "'" ;
		$this->_db->setQuery($query);
		if($this->_db->query() === false) return false ;

		// exterminate orphans
		$query = "DELETE FROM #__custom_properties
			WHERE field_id = '" . $this->id . "'";
		$this->_db->setQuery($query);
		if($this->_db->query() === false) return false ;

		return true;
	}

  function _isInt ($x) {
      return (is_numeric($x) ? intval($x) == $x : false);
  }
}
?>
