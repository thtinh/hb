<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.4 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
/**
 * Custom Poperties Content Model - retrieves taggable content items
 *
  * @package Custom Properties
  * @version 1.90
  * @subpackage Component
 */
class CustompropertiesModelContent extends JModel {
	/**
	* Field id
	* @var integer
	*/
	var $_id;
	/**
	 * CP Fields list
	 * @var array
	 */
	var $_list;
	/**
	 * Pagination
	 *
	 * @var array
	 */
	var $_page;

	/**
	 * Retrieves all content elements of choosen type
	 * @param object cpContentElement $content_element Content elements
	 * @return array Array of objects containing the data from the database
	 */
	function getList($content_element) {

		if(!$content_element) return false;

		global $mainframe;

		$database = JFactory::getDBO();

		if (!empty ($this->_list)) {
			return $this->_list;
		}

		$wherestr	= array();
		$selstr		= array();
		$fromstr	= array();
		$orderstr	= array();

		$limit 		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

		$selstr[] = "c.".$content_element->id." AS id ";
		$selstr[] = "c.".$content_element->title." as title ";
		$selstr[] = "c.".$content_element->state ." as state ";
		$fromstr[]= "#__".$content_element->table." AS c ";
		if($content_element->sec_table){
			$section_id = JRequest::getVar('filter_sectionid', 0, '', 'int');
			$selstr[]	= "IFNULL(sec.".$content_element->sec_table_title.", 'Static') AS section ";
			$fromstr[] 	= "LEFT JOIN #__".$content_element->sec_table." AS sec " .
							"ON(c.".$content_element->sectionid." = sec.".$content_element->sec_table_id.") ";
			if($section_id != "" && $content_element->sec_table) $wherestr[]= "AND c.".$content_element->sectionid." = $section_id ";
			$orderstr[] = "c.".$content_element->sectionid;
		}
		else{
			$selstr[] = "'' AS section ";
		}

		if($content_element->cat_table){
			$category_id 	= JRequest::getVar('filter_categoryid', 0, '', 'int');
			$selstr[]		= "IFNULL(cat.".$content_element->cat_table_title.", 'Static') AS category ";
			$fromstr[]		= " LEFT JOIN #__".$content_element->cat_table." cat " .
								"ON(c.".$content_element->catid." = cat.".$content_element->cat_table_id.") " ;
			if($category_id != ""  && $content_element->cat_table) $wherestr[] = "AND c.".$content_element->catid." = $category_id  ";
			$orderstr[] = "c.".$content_element->catid;
		}
		else{
			$selstr[]		= "'' AS category ";
		}

		$filter_title 		= JRequest::getVar('filter_title', '');
		if($filter_title != "" ) $wherestr[] = "AND c.".$content_element->title." LIKE '%$filter_title%'  ";
		// default ordering
		$orderstr[] = "c.". $content_element->title ;

		// put the query together
		$selstr 	= join($selstr, ',');
		$fromstr 	= join($fromstr, ' ');
		$wherestr 	= join($wherestr, ' ');
		$orderstr	= join($orderstr, ',');
		$query = "SELECT  SQL_CALC_FOUND_ROWS $selstr " .
				"FROM $fromstr " .
				"WHERE 1 $wherestr " .
				"ORDER BY $orderstr";

		$database->setQuery($query, $limitstart, $limit);
		$this->_list = $database->loadObjectList();

		// If there is a db query error, throw a HTTP 500 and exit
		if ($database->getErrorNum()) {
			JError::raiseError(500, $database->stderr());
			return false;
		}
		else{
			$database->setQuery('SELECT FOUND_ROWS();');  //no reloading the query! Just asking for total without limit
			jimport('joomla.html.pagination');
			$this->_page = $pageNav = new JPagination($database->loadResult(), $limitstart, $limit);
		}

		return $this->_list;

	}

	function getPagination($content_element) {

		if(!$content_element) return false;

		if (is_null($this->_list) || is_null($this->_page)) {
			$this->getList($content_element);
		}
		return $this->_page;
	}

}