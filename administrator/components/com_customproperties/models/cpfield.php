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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Customproperties Field Model
 *
  * @package Custom Properties
  * @version 1.90
  * @subpackage Component
 */
class CustompropertiesModelCpfield extends JModel {
	/**
	* Field id
	*
	* @var integer
	*/
	var $_id;
	/**
	 * CP Field data
	 *
	 * @var array
	 */
	var $_data;
	/**
	 * CP Values array
	 *
	 * @var array
	 */
	var $_values;

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access    public
	 * @return    void
	 */
	function __construct() {
		parent::__construct();

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);
	}

	/**
	 * Method to set the CP Field identifier
	 *
	 * @access    public
	 * @param    int CP Field identifier
	 * @return    void
	 */
	function setId($id) {
		// Set id and wipe data
		$this->_id = $id;
		$this->_data = null;
	}

	/**
	 * Retrieves the CP Field data
	 * @return array Array of objects containing the data from the database
	 */
	function & getData() {
		// Load the data
		if (empty ($this->_data)) {
			$database = $this->_db;
			/* retrieve record */
			$query = 'SELECT * FROM #__custom_properties_fields' . ' WHERE id = ' . $this->_id . ' ORDER BY ordering ';

			$database->setQuery($query);
			$this->_data = $database->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = '';
			$this->_data->label = '';
			$this->_data->access = 0;
			$this->_data->published = 0;
			$this->_data->field_type = null;
			$this->_data->modules = '';
		}
		return $this->_data;
	}

	/**
	 * Retrieves the CP Field values array
	 * @return array Array of objects containing the Values from the database
	 */
	function & getValues() {
		// Load the data
		if (empty ($this->_values)) {
			$database = $this->_db;
			/* retrieve values */
			$query = 'SELECT * FROM #__custom_properties_values' . ' WHERE field_id = ' . $this->_id . ' ORDER BY ordering ';

			$database->setQuery($query);
			$this->_values = $database->loadObjectList();
		}
		return $this->_values;
	}

	/**
	 * Method to change fields order my moving records up or down of 1 position
	 * @param int 1 , -1
	 * @return void
	 */
	function orderField($direction) {
		if ($this->_id) {
			$row = & JTable::getInstance('cpfield', 'Table');
			$row->load($this->_id);
			$row->move($direction);
		}
	}

	/**
	 * Method to save fields order
	 * @return void
	 */
	function saveFieldsOrder() {
		$cid = JRequest::getVar('cid', '', '', 'array');
		$total = count($cid);
		$order = JRequest::getVar('order', '', '', 'array');
		$field = & JTable::getInstance('cpfield', 'Table');

		// update ordering values
		for ($i = 0; $i < $total; $i++) {
			$field->load((int) $cid[$i]);
			if ($field->ordering != $order[$i]) {
				$field->ordering = $order[$i];
				if (!$field->store()) {
					JError::raiseError(500, JText::_('Error saving fields order.'));
				}
			}
		}
		$field->reorder();
	}

	/**
	 * Method to publish / unpublish one ore more fields
	 * @return void
	 */
	function publishFields() {
		$cid = JRequest::getVar('cid', '', '', 'array');
		$field = & JTable::getInstance('cpfield', 'Table');
		$action = JRequest::getCmd('task') == 'publish' ? 1 : 0;
		$result = $field->publish($cid, $action);
	}

	/**
	 * Method to delete one or more fields
	 * @return void
	 */
	function deleteFields() {
		$cid = JRequest::getVar('cid', '', '', 'array');
		$field = & JTable::getInstance('cpfield', 'Table');

		foreach ($cid as $recordID) {
			$field->load($recordID);
			$field->delete();
		}
	}
	/**
	 * Method for saving fields
	 *
	 * @access public
	 * @returns true if save is successful, an array of errors otherwise
	 */
	function saveField() {

		$array = array ();
		$array['id'] 			= $id = JRequest::getVar('cid', 0, '', 'int');
		$array['name']			= $this->_fixName(JRequest::getVar('name', '', '', 'string'));
		$array['label'] 		= $this->_fixLabel(JRequest::getVar('label', '', '', 'string'));
		$array['field_type']	= JRequest::getVar('field_type', '', '', 'string');
		$array['published']		= JRequest::getVar('published', '', '', 'int');
		$array['access'] 		= JRequest::getVar('access', '', '', 'int');
		$array['ordering'] 		= JRequest::getVar('ordering', '', '', 'int');
		$array['modules'] 		= implode(',', JRequest::getVar('modules', '', '', 'array'));

		// init db object
		$field = & JTable::getInstance('cpfield', 'Table');
		// set ordering for new fields
		if ($id == 0)
			$array['ordering'] = $field->getNextOrder();
		//  bind temp array to db object
		$field->bind($array);
		// validity check
		if ($field->check()) {
			// save field data
			$field->store();
			$this->_id = $field->id;
			// save values
			if ($id > 0) { // saving values only if not a new field
				$save_value_result = $this->_saveValues();
				if ($save_value_result === true) {
					return true;
				} else {
					// return errors array
					$this->_errors = $save_value_result;
					return false;
				}
			} else {
				return true;
			}
		} else {
			$this->_errors = $field->getErrors();
			return false;
		}
	}

	/**
	 * Method to change values order my moving records up or down of 1 position
	 * @param int 1 , -1
	 * @return void
	 */
	function orderValue($direction) {
		$cid = JRequest::getVar('cid', '', '', 'array');
		$val_id = JRequest::getVar('val_id', '', '', 'array');
		$field_id = $cid[0];
		$value_id = $val_id[0];

		if (!empty ($field_id) && !empty ($value_id)) {
			$value = & JTable::getInstance('cpvalue', 'Table');
			$value->load($value_id);
			$value->move($direction, " field_id = '$field_id' ");
			$value->reorder(" field_id = '$field_id' ");
		}
	}
	/**
	 * Method to delete value(s)
	 * @return void
	 */
	function deleteValue() {
		$val_id = JRequest::getVar('val_id', '', '', 'array');
		$value = & JTable::getInstance('cpvalue', 'Table');
		foreach ($val_id as $value_id) {
			$value->load($value_id);
			$value->delete();
		}
	}
	/**
	 * Method for saving values
	 *
	 * @access private
	 * @returns true if save is successful, an array of errors otherwise
	 */

	function _saveValues() {
		/* parent field */
		$cid = JRequest::getVar('cid', '', '', 'array');
		$field_id = $cid[0];

		/* we expect an array for every field */
		$ids 		= JRequest::getVar('value_id', 		null, 	'', 'array');
		$names 		= JRequest::getVar('value_name', 	null, 	'', 'array');
		$labels 	= JRequest::getVar('value_label', 	'', 	'', 'array');
		$priorities	= JRequest::getVar('value_priority','', 	'', 'array');
		$defaults 	= JRequest::getVar('value_default', '', 	'', 'array');
		$orders 	= JRequest::getVar('value_order', 	'', 	'', 'array');

		$new_ordering = max(array_values($orders)) + 1;
		$errors = array ();
		$array = array ();
		$got_default = false;

		if (!empty ($ids)) {
			foreach ($ids as $key => $value) {
				$array['id'] 		= $ids[$key];
				$array['field_id'] 	= $field_id;
				$array['name'] 		= $this->_fixName($names[$key]);
				$array['label'] 	= $this->_fixLabel($labels[$key]);
				$array['priority'] 	= $priorities[$key];

				if ($got_default == false && $defaults[$key] == 1) { // only one can be a default value
					$array['default'] = 1;
					$got_default = true;
				} else {
					$array['default'] = 0;
				}

				if ($orders[$key] == '0' || $orders[$key] == null) {
					$array['ordering'] = $new_ordering++;
				} else {
					$array['ordering'] = $orders[$key];
				}

				// init db object
				$value = & JTable::getInstance('cpvalue', 'Table');
				//  bind temp array to db object
				$value->bind($array);
				// validity check
				if ($value->check()) {
					// save field data
					$value->store();
				} else {
					// collect errors but go on
					$errors[] = "[ Value " . $value->id . " error: " . $value->getError() . " ]";
				}

			}
			$value->reorder(" field_id = '$field_id' ");
		}

		if (count($errors)) {
			return $errors;
		} else {
			return true;
		}
	}

	/**
	 * Method to clean field and/or value name
	 * converts spaces to underscores e strips non word chars
	 *
	 * @access private
	 * @param string
	 * @returns string
	 */
	function _fixName($field_or_value_name) {
		if ($field_or_value_name == "")
			return "";
		// convert space into underscore
		$result = preg_replace("/ /", "_", strtolower($field_or_value_name));
		// strip all non alphanumeric chars
		$result = preg_replace("/\W/", "", $result);
		return $result;
	}
	/**
	 * Method to clean field and/or value labes
	 * converts spaces to underscores and trimps unnecessary spaces
	 * can be tweaked to remove non word chars , but, depending on
	 * preg_replace implementation, it could also remove accended chars et similia
	 * Default : "strange chars" are left in place
	 *
	 * @access private
	 * @param string
	 * @returns string
	 */
	function _fixLabel($field_or_value_label) {
		/* clean field and/or value name */
		if ($field_or_value_label == "")
			return "";
		//strip slash, column and comma
		$result = preg_replace("/[:,\/,\,]/", "", $field_or_value_label);
		// change space in to underscore
		$result = preg_replace("/ /", "_", $result);
		// strip all non alphanumeric chars
		// uncomment next line if you do not want "strange" chars in label
		// $result = preg_replace("/\W/","", $result);
		// change underscores back to spaces
		$result = preg_replace("/_/", " ", trim($result));
		return $result;
	}
}