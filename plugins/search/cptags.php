<?php
/**
* Joomla Custom Properties
* @package Custom Properties
* @subpackage cptags.php - Custom Properties content mambot
* @version 1.98.1
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onSearch', 'plgSearchCptags' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchCptagsAreas' );

JPlugin::loadLanguage( 'com_customproperties' );

/**
 * @return array An array of search areas
 */
function &plgSearchCptagsAreas()
{
	static $areas = array(
		'content' => 'Tags'
	);
	return $areas;
}

/**
 * Content Search method
 * The sql must return the following fields that are used in a common display
 * routine: href, title, section, created, text, browsernav
 * @param string Target search string
 * @param string mathcing option, exact|any|all
 * @param string ordering option, newest|oldest|popular|alpha|category
 * @param mixed An array if the search it to be restricted to areas, null if search all
 */
function plgSearchCptags( $text, $phrase='', $ordering='', $areas=null )
{

	require_once(JPATH_ROOT .DS. 'administrator'.DS.'components'.DS.'com_customproperties'.DS.'contentelement.class.php');
	require_once(JPATH_SITE .DS. 'components'.DS.'com_customproperties'.DS.'models' . DS . 'search.php');

	global $mainframe;
	$database 	= JFactory::getDBO();
	$language 	= $mainframe->getCfg('language');
	$user		=& JFactory::getUser();
	$aid 		= $user->get('aid');
 	$result = null;

	$search = new CustompropertiesModelSearch();
	$got_JoomFish = $search->_params->get('use_joomfish');

 	if($got_JoomFish){
 		$query = "SELECT DISTINCT v.id " .
 			"FROM #__custom_properties_fields AS f " .
			"INNER JOIN #__custom_properties_values as v ON f.id = v.field_id " .
 			"LEFT JOIN #__jf_content as jfc ON jfc.reference_id = v.id " .
 			"LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id " .
 			"WHERE f.access <= '$aid' " ;
 	}
 	else{
 		$query = "SELECT v.id " .
 			"FROM #__custom_properties_fields as f " .
 			"INNER JOIN #__custom_properties_values as v " .
 			"ON (f.id = v.field_id) " .
 			"WHERE f.access <= '$aid' " ;
 	}

	$wherestr = array();
	switch($phrase){
		default;
		case 'exact':
		 	if($got_JoomFish){
		 		$query .= "AND ( ( jfc.value LIKE '$text' AND jfl.code = '$language') " .
		 			" OR v.label = '$text' ) ";
		 	}
		 	else{
		 		$query .= "AND v.label = '$text' ";
		 	}
		break;
		case 'any':
			$words = split(' ', $text);

		 	if($got_JoomFish){
		 		foreach($words as $word){
		 			$wherestr[] = "( ( jfc.value LIKE '$word' AND jfl.code = '$language') OR v.label = '$word' ) ";
		 		};
		 	}
		 	else{
		 		foreach($words as $word){
		 			$wherestr[] = "v.label = '$word' ";
		 		};
		 	}

		 	$query .= ' AND (' . join($wherestr, ' OR ') . ')';
		 break;

	}

	$database->setQuery($query);
	$result = $database->loadResultArray();

	$search = new CustompropertiesModelSearch();
	$rows = array();

	foreach($result as $tagId) {
		$rows = $search->getData($tagId);
		array_merge($rows, (array) $rows);
	}

	//CP generated arrays are different than standard search ones
	$search_rows = array();
	foreach($rows as $k => $ce_result){ //cycle the content elements

		foreach((array) $ce_result as $row){

			$new_row = array(
				'title' 	=> $row->title,
				'metadesc' 	=> '',
				'metakey' 	=> '',
				'created'	=> $row->created,
				'text'		=> $row->introtext . ' ' . $row->fulltext,
				'section'	=> $row->section,
				'slug'		=> $row->id . ':' . $row->title,
				'catslug'	=> '',
				'sectionid'	=> $row->secid,
				'browsernav'=> '2',
				'href'		=> $row->href
			);

			$search_rows[] = (object) $new_row;
		}
	}

	return $search_rows;

}

