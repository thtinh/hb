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
 * Customproperties Values Table class
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class TableCpvalue extends JTable
{
	/* record id */
	var $id = 0;
	/* parent field */
	var $field_id = null;
	/* field name (and value to be forwarded when submitted) */
	var $name = null ;
	/* parent field */
	var $label = null;
	/* priority : 0 being the most important, 2 the least important default 0 */
	var $priority = null;
	/* default */
	var $default = null;
	/* ordering */
	var $ordering = null;

  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function TableCpvalue( &$db ) {
      parent::__construct('#__custom_properties_values', 'id', $db);
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

		/* validity check */
		if( !$this->_isInt($this->id) || $this->id < 0){
			$this->setError( JText::_("Wrong ID") );
			return false;
		}
		if( !$this->_isInt($this->field_id) || $this->field_id < 0){
			$this->setError( JText::_("Wrong field id") );
			return false;
		}
		if( !$this->_isInt($this->ordering) || $this->ordering < 0){
			$this->setError( JText::_("WRONG ORDER") );
			return false;
		}
		// check if parent exists
		$parent_field = & JTable::getInstance('cpfield', 'Table');
		if(! $parent_field->load($this->field_id) ){
			$this->setError( JText::_("NO PARENT") );
			return false;
		}
		if(  !$this->_isInt($this->id) && !$this->_isInt($this->priority) ){
			$this->setError( JText::_("WRONG PRIORITY") );
			return false;
		}
		if( $this->default != '0' && $this->default != '1'){
			$this->setError( JText::_("WRONG DEFAULT") );
			return false;
		}
		return true;
	}


  /**
   * Overloaded delete function. Deletes the field AND its values AND links
   * @return boolean true if check is positive, false otherwise
   */
	function delete(){
		// delete value AND records from customProperties
		$query = "DELETE FROM #__custom_properties_values
			WHERE id = '" . $this->id . "'" ;
		$this->_db->setQuery($query);
		if($this->_db->query() === false) return false ;

		// exterminate orphans
		$query = "DELETE FROM #__custom_properties
			WHERE value_id = '" . $this->id . "'";
		$this->_db->setQuery($query);
		if($this->_db->query() === false) return false ;

		return true;
	}

  function _isInt ($x) {
      return (is_numeric($x) ? intval($x) == $x : false);
  }
}
