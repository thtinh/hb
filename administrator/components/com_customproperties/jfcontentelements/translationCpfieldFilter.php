<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.2 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Don't allow direct linking
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

class translationCpfieldFilter extends translationFilter
{
	function translationCpfieldFilter ($contentElement){
		$this->filterNullValue=-1;
		$this->filterType="cpfield";
		$this->filterField = $contentElement->getFilter("cpfield");
		parent::translationFilter($contentElement);

	}

	/**
 * Creates vm_pollname filter
 *
 * @param unknown_type $filtertype
 * @param unknown_type $contentElement
 * @return unknown
 */
	function _createfilterHTML(){
		$database = JFactory::getDBO();

		if (!$this->filterField) return "";

		$fieldnameOptions=array();
		$fieldnameOptions[] = JHTML::_('select.option', '-1', JText::_('All Fields') );

		$sql = "SELECT DISTINCT f.id, f.label
      FROM #__custom_properties_fields as f, #__".$this->tableName." as v
			WHERE v.".$this->filterField."=f.id ORDER BY f.label";

		$database->setQuery($sql);
		$fields = $database->loadObjectList();
		$catcount=0;
		foreach($fields as $field){
			$fieldnameOptions[] = JHTML::_('select.option', $field->id,$field->label);
			$catcount++;
		}
		$fieldnameList=array();
		$fieldnameList["title"]= JText::_('Which Field');
		$fieldnameList["html"] = JHTML::_('select.genericlist', $fieldnameOptions, 'cpfield_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );

		return $fieldnameList;
	}

}
?>
