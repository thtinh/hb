<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.6 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

/**
 * Field Assignment View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewAssign extends JView {
	/**
	 * CP Fields assign method
	 * @return void
	 **/
	function display($tpl = null) {

		// content element
		$ce_name		 		= JRequest::getVar('ce_name', null, '', 'string');
		if(! $content_element 	= getContentElementByName("$ce_name")) {
			$content_element 	= getContentElementByName("content");
			$ce_name			= 'content';
		}

		// content items
		$content 	= $this->getModel('content', 'custompropertiesModel');
		$items 		= $content->getList($content_element);
		$page		= $content->getPagination($content_element);

		// CP fields
		$cp 		= $this->getModel('cpfields', 'custompropertiesModel');
		$cpfields 	= $cp->getList(false);

		// properties
		$assign 	= & $this->getModel();
		$content_id = $assign->_id;
		$item_title = $assign->getTitle();

		$this->assignRef('items',			$items);
		$this->assignRef('page',			$page);
		$this->assignRef('content_element', $content_element);
		$this->assignRef('ce_name',			$ce_name);
		$this->assignRef('cpfields',		$cpfields);
		$this->assignRef('content_id',		$content_id);
		$this->assignRef('item_title',		$item_title);

		parent::display($tpl);
	}

}

/** generates a CP Content Element Select list
 * @param string $field_name name of the select element
 * @param mixed $active array or id of the active element
 * @param string $javascript javascript
 * @return html string for the select
 */
function SelectContentElement($field_name, $active = null, $javascript){
	$content_elements = getInstalledContentElements();
	if(count($content_elements) < 2) return '';

	foreach($content_elements as $ce){
		$contentelements[]	= JHTML::_('select.option', $ce->name, $ce->label );
	}
	return	'Content Type: '. JHTML::_('select.genericlist', $contentelements, $field_name, $javascript.' class="inputbox" size="1"', 'value', 'text', $active );
}

/** generates a Section select list for the passed content element
 * @param object $content_elelemt content_element to generate the list for
 * @param string $field_name name of the select element
 * @param mixed $active array or id of the active element
 * @param string $javascript javascript
 * @return html string for the select or false if error occurs
 */
function SelectSection($content_element, $field_name, $active = null, $javascript){
	if(empty($content_element->sec_table)) return false;

	$id_field 	= $content_element->sec_table_id;
	$name_field = $content_element->sec_table_title;

	$database = JFactory::getDBO();
	$query = "SELECT * " .
			" FROM #__" . $content_element->sec_table .
			" ORDER BY ". $name_field ;

	$database->setQuery($query);
	$values = $database->loadObjectList();

	$sections[] = JHTML::_('select.option','',"- Section -");
	foreach($values as $value){
		$sections[]	= JHTML::_('select.option', htmlspecialchars($value->$id_field), htmlspecialchars($value->$name_field));
	}
	return	JHTML::_('select.genericlist', $sections, $field_name, $javascript.' class="inputbox" size="1"', 'value', 'text', $active );
}

/** generates a Category select list for the passed content element
 * @param object $content_elelemt content_element to generate the list for
 * @param string $field_name name of the select element
 * @param string $section section id of parent section
 * @param mixed $active array or id of the active element
 * @param string $javascript javascript
 * @return html string for the select or false if error occurs
 */
function SelectCategory($content_element, $field_name, $section=null, $active = null, $javascript){
	if(empty($content_element->cat_table)) return false;

	$database = JFactory::getDBO();

	$id_field 	= $content_element->cat_table_id;
	$name_field = $content_element->cat_table_title;

	$query = "SELECT * " .
			" FROM #__" . $content_element->cat_table .
			" WHERE 1 ";
	if($content_element->cat_parent_section_table && $section){
		$query .= " AND " . $content_element->cat_parent_section_field . "= '$section' ";
	}
	elseif($content_element->cat_section_filter){
		$query .= " AND " . $content_element->cat_parent_section_field . "= '".$content_element->cat_section_filter."' ";
	}
	$query .= " ORDER BY ". $name_field ;

	$database->setQuery($query);
	$values = $database->loadObjectList();

	$categories[] = JHTML::_('select.option','',"- Category -");
	foreach($values as $value){
		$categories[]	= JHTML::_('select.option', htmlspecialchars($value->$id_field), htmlspecialchars($value->$name_field));
	}
	return	JHTML::_('select.genericlist', $categories, $field_name, $javascript.' class="inputbox" size="1"', 'value', 'text', $active );
}