<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.8 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

require_once(JPATH_ROOT .DS. 'administrator'.DS.'components'.DS.'com_customproperties'.DS.'contentelement.class.php');

class CustompropertiesModelSearch extends JModel
{
	/**
	* Search data array
	*
	* @var object
	*/
	var $_data;
	/**
	* Rendering parameters
	*
	* @var object
	*/
	var $_params;
	/**
	* Search parameters
	*
	* @var array
	*/
	var $_search_pars;
	/**
	 * Result summary
	 *
	 * @var object
	 */
	var $_result_summary;
	/**
	 * Pagination
	 *
	 * @var object
	 */
	var $_page;
	/**
	* Tag name passed as a search key
	*
	* @var string
	*/
	var $_tagname;
	/**
	* Searchword passed for text search
	*
	* @var string
	*/
	var $_searchword;
	/*
	 * Result ordering
	 */
	var $_ordering;

	/**
	 * Constructor
	 *
	 * @access		public
	 * @return		void
	 */
	function __construct(){

		parent::__construct();

		global $mainframe, $cp_config;

		$session = JFactory::getSession();
		// configuration file
		require_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_customproperties'. DS . 'cp_config.php');
		if(empty($cp_config)){
			JError::raiseError( 500, JText::_( "CP_NON_CONFIG" ) );
		}

		$params = new JParameter( '' );
		$params->def( 'pageclass_sfx',			$cp_config['pageclass_sfx'] );
		$params->def( 'show_page_title',		$cp_config['show_page_title'] );
		$params->def( 'header',				 	$cp_config['header'] != '' ? 			$cp_config['page_title'] 		: JText::_('Search') );
		$params->def( 'use_joomfish',			$cp_config['use_joomfish'] != "" ?		$cp_config['use_joomfish']		: '0');
		$params->def( 'script_position',		$cp_config['script_position'] != "" ? 	$cp_config['script_position'] 	: 'auto');
		$params->def( 'search_unauthorized',	$cp_config['search_unauthorized']!= "" ? $cp_config['search_unauthorized'] : '1');
		$params->def( 'search_archived',		$cp_config['search_archived'] != "" ? 	$cp_config['search_archived']	: '1');
		$params->def( 'use_itemid',				$cp_config['use_itemid'] != "" ?	 	$cp_config['use_itemid']		: '1');
		$params->def( 'result_acl',		 		$cp_config['result_acl'] );
		$params->def( 'frontend_tagging',		$cp_config['frontend_tagging'] != '' ? 	$cp_config['frontend_tagging'] 	: '0') ;
		$params->def( 'editing_level',			$cp_config['editing_level'] != '' ? 	$cp_config['editing_level'] 	: '1') ;

		$params->def( 'limit',					$cp_config['limit'] != '' ? 			$cp_config['limit'] 			: $mainframe->getCfg('list_limit') );
		$params->def( 'search_sections',		preg_replace( '/[^0-9\,]/', '', 		$cp_config['search_sections'] ));
		$params->def( 'default_ordering',		$cp_config['default_ordering']);
		$params->def( 'ordering_field',		 	$cp_config['ordering_field'] != "" ? 	$cp_config['ordering_field']	: '0' );

		$params->def( 'use_cp_css', 			$cp_config['use_cp_css'] != "" ? 		$cp_config['use_cp_css'] 		: '1');
		$params->def( 'show_content_element_label', $cp_config['show_content_element_label'] != "" ? $cp_config['show_content_element_label'] : '0');
		$params->def( 'view', 					$cp_config['view'] != "" ? 				$cp_config['view'] 				: '2');
		$params->def( 'allowed_tags',			$cp_config['allowed_tags'] != "" ? 		$cp_config['allowed_tags'] 		: "<h3><h4><h5><a><p>");
		$params->def( 'text_length',			$cp_config['text_length'] != '' ?		$cp_config['text_length'] 		: '200');

		$params->def( 'show_result_summary',	$cp_config['show_result_summary'] != '' ? 	$cp_config['show_result_summary']	: '0') ;
		$params->def( 'show_content_element', 	$cp_config['show_content_element'] != "" ? 	$cp_config['show_content_element'] 	: '1');
		$params->def( 'show_create_date', 		$cp_config['show_create_date'] != "" ? 		$cp_config['show_create_date'] 		: '0');
		$params->def( 'show_section',	 		$cp_config['show_section'] != "" ? 			$cp_config['show_section']			: '0');
		$params->def( 'linked_result_summary',	$cp_config['linked_result_summary'] != '' ? $cp_config['linked_result_summary'] : '1') ;

		$params->def( 'search_header_tl', 		$cp_config['search_header_tl'] != "" ? 	$cp_config['search_header_tl'] 	: "1");
		$params->def( 'search_header_tr', 		$cp_config['search_header_tr'] != "" ? 	$cp_config['search_header_tr'] 	: "2");
		$params->def( 'search_header_bl', 		$cp_config['search_header_bl'] != "" ? 	$cp_config['search_header_bl'] 	: "3");
		$params->def( 'search_header_br', 		$cp_config['search_header_br'] != "" ? 	$cp_config['search_header_br'] 	: "4");

		$params->def( 'image_thumbnail', 		$cp_config['image_thumbnail'] != "" ? 	$cp_config['image_thumbnail'] 	: "1");
		$params->def( 'thumb_width', 			$cp_config['thumb_width'] != "" ? 		$cp_config['thumb_width'] 		: "100");
		$params->def( 'thumb_height',			$cp_config['thumb_height'] != "" ? 		$cp_config['thumb_height'] 		: "100");
		$params->def( 'keep_aspect', 			$cp_config['keep_aspect'] != "" ? 		$cp_config['keep_aspect'] 		: "1");
		$params->def( 'image_quality', 			$cp_config['image_quality'] != "" ? 	$cp_config['image_quality'] 	: "75");
		$params->def( 'debug', 					$cp_config['debug'] != "" ? 			$cp_config['debug'] 			: "0");

		$params->def( 'show_tags',				$cp_config['show_tags'] != "" ?			$cp_config['show_tags']			: '0');
		$params->def( 'linked_tags',			$cp_config['linked_tags'] != '' ? 		$cp_config['linked_tags'] 		: '1') ;
		$params->def( 'show_tag_name',			$cp_config['show_tag_name'] != "" ? 	$cp_config['show_tag_name']		: '0');
		$params->def( 'url_format',				$cp_config['url_format'] != "" ? 		$cp_config['url_format']		: '0');
		$this->_params = & $params;

		//USING session to store default co ordering
		if($ordering = JRequest::getVar('ordering')){
			$this->_ordering = $ordering;
			$session->set('cp_ordering', "$ordering");
		}
		elseif($ordering = $session->get('cp_ordering')){
			$this->_ordering = $ordering;
		}
		else{
			$this->_ordering = $cp_config['default_ordering'];
		}

	}
	/** Function that returns data for all content elements.
	 * It also fills Search summary, Page nav and search result
	 * @param mixed $tag  when set, returns all rows with the relevant tag
	 * @return void
	 */
	function getData($tag = ""){

		$user 			= JFactory::getUser();
		$aid			= $user->get('aid');
		$params 		= $this->_params;
		$ordering 		= $this->_ordering;
		$search_pars	= array();
		$this->_errors 	= array(); // reset errors

		if($aid < $this->_params->get('result_acl') ) {
			$this->setError("result_acl");
		}

		if($tag != ""){
			// add tag to page title
			if($tagname = getTagNamebyId($tag, $params)){
				$this->_tagname = $tagname;
			}
		}

		// filter by content element type
		$content_elements = array ();
		if ($content_element = JRequest::getVar('content_element')) {
			if ($ce = getContentElementByName($content_element)) {
				$content_elements[] = $ce;
			}
		}
		// I didn't get any valid content element, get all avaible
		if (!$content_elements) {
			$content_elements = getInstalledContentElements();
		}

		$total = 0;
		$cpq = array ();
		foreach ($content_elements as $key => $ce) {
			$cpq[$ce->name] = new CPQuery();
			if ($buffer = $cpq[$ce->name]->getRows($ce, $this->_search_pars, $tag, null, $ordering, 0, $params)) {
				$result[$ce->name] = $buffer;
				$total += count($result[$ce->name]);
			}
			elseif ($buffer === false) {
				foreach ($cpq[$ce->name]->errors as $error_code) {
					$this->setError($error_code);
				}
				return;
			}
			elseif (count($buffer) === 0) {
				/* this ce does not contain any matching record,
				* remove it from content_elements list so that we don't process
				* it nay further
				*/
				unset ($content_elements[$key]);
			}
		}

		// no point in creating pagination or summary
		// with no result to show
		if($total === 0){
			$this->setError("no match");
			return ;
		}

		//PAGINATION
		$limit      = JRequest::getVar('limit', $params->get('limit'), '', 'int' );
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int' );

		jimport('joomla.html.pagination');
		if ($total <= $limitstart) $limitstart = 0;
		$pageNav = new JPagination($total, $limitstart, $limit);
		$this->_page =& $pageNav;

		/* creates a Result summary that is a list of sections with relevant number of articles found	*/
		if ($params->get('show_result_summary')) {
			$this->_result_summary = $this->_createResultSummary($content_elements, $cpq, $ordering);
		}

		// unset rows out of pagination
		$i = 0;
		foreach ($result as $key => $value) {
			foreach ($result[$key] as $ce => $val) {
				if ($i < $limitstart || $i >= $limitstart + $limit) {
					unset ($result[$key][$ce]);
				}
				$i++;
			}
		}

		$this->_data = $result;
		return $result;

	}

	/**
	 * Method to get pagination object
	 *
	 * @returns object
	 */
	function getPagination($tags="")
	{
		if (is_null($this->_page)) {
			$this->getData($tags="");
		}
		return $this->_page;
	}
	/**
	 * Method to get the result summary
	 *
	 * @return object result summary object
	 */
	function getResultSummary($tags=""){
		if(is_null($this->_result_summary)){
			$this->getData($tags);
		}
		return $this->_result_summary;
	}
	/**
	 * Method to get the search parameters
	 *
	 * @return array search parameters array
	 */
	function getSearchPars($tags=""){
		if(is_null($this->_search_pars)){
			$this->getData($tags);
		}
		return $this->_search_pars;
	}
	/**
	 * Method to get the tag name
	 *
	 * @return string the tag name
	 */
	function getTagName(){
		return $this->_tagname;
	}
	/**
	 * Method to get the component parameters
	 *
	 * @return array component parameters
	 */
	function getParams(){
		return $this->_params;
	}


	/** returns the result summary, an object array
	 * @param cpContentElement array $content_elements array of cp content elements
	 * @param cpQuery array	$cp_query array of cpQuery
	 * @param string $ordering ordering, to be propagated
	 * @return array of objects result summary object
	 */
	function _createResultSummary( $content_elements, $cp_query ){

		$database		= JFactory::getDBO();
		$search_pars 	= $this->_search_pars;
		$result 		= array();

		/* build the link serializing search pars*/
		$link = "index.php?option=com_customproperties";
		foreach($search_pars as $key => $value){
			if(is_array($value)){
				foreach($value as $v){
					$link .= '&'.$key.'[]='.$v;
				}
			}
			else{
				$link .= '&'.$key.'='.$value;
			}
		}

		$selected = JRequest::getVar('show_section','') == '' ? 1 : 0;
		$result[0] = new resultSummaryRow('', '', JText::_('All Sections'),$link, '', $selected);

		foreach($content_elements as $ce){

			$selstr = array();
			$groupstr = "";
			$selstr[] = "'" .$ce->name . "' AS ce";
			if($ce->sec_table) {
				$selstr[] = "sec.id";
				$selstr[] = "sec.title AS section";
				$groupstr = "GROUP BY sec.title";
			}
			else {
				$selstr[] = "'' as id";
				$selstr[] = "'".$ce->label."' AS section";
				$groupstr = "GROUP BY id";
			}

			$selstr[] = "COUNT(DISTINCT c.". $ce->id. ") AS cnt";
			$selstr = join($selstr, ',');
			$query = "SELECT $selstr
				FROM  " . $cp_query[$ce->name]->fromstr ."
				WHERE " . $cp_query[$ce->name]->wherestr  .
				$groupstr ;

			$database->setQuery($query);
			$rows = $database->loadObjectList();
			JArrayHelper::sortObjects($rows, 'section');

			$result[$ce->name] = array();
			foreach($rows as $row){
				$selected 	= JRequest::getVar('show_section') == $row->id ? 1 :0;
				$ceLink 	= $link."&content_element=".$ce->name;
				$secLink 	= $link."&content_element=".$ce->name."&show_section=".$row->id;
				$result[$ce->name][] = new resultSummaryRow($ce->label, $ceLink, $row->section, $secLink, $row->cnt, $selected );
			}
		}

		return $result;
	}
}

/**
 * This class puts together and executes the query to retrieve the tagged content items.
 * Stores into its variables:
 * @var string the FROM part of the query
 * @var string the WHERE part of teh query
 * @var searchword the searched (as in texr search) word
 * @var array errors array
 */
class CPQuery{
	var $fromstr = null;
	var $wherestr = null;
	var $searchword = null;
	var $errors = array();

	/** This function buid the query to retrive content items
	 * based on assigned tags
	 * @param cpContentElement object $ce cp Custom Properties Object
	 * @param array &$search_pars array search_field=>search_value
	 * @param integer/array $tags tag id, if set, the search by tag is performed. If tags is an array, content items with all tag will be retrieved.
	 * @param integer $exclude_id article id to be excluded from results. Used for related items, for not showing the article itself.
	 * @param string $ordering ordering of the dataset
	 * @param object &$params component parameters
	 * @param integer $limit limit returned rows
	 * @return object returns the relevant rows or false if an error occurs
	 */
	function getRows($ce, &$search_pars, $tags=null,$exclude_id=null, $ordering, $limit=0,  $params){

		global $mainframe;
		$user 		= JFactory::getUser();
		$aid		= $user->get('aid',0);
		$database 	= JFactory::getDBO();
		$language 	= $mainframe->getCfg('language');

		$searchword = "";
		$result 	= array();
		$nullDate 	= $database->getNullDate();
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();
		if(is_array($exclude_id)) $exclude_id = join($exclude_id, ',');

		$got_JoomFish = $params->get('use_joomfish');

		$selstr		= array();
		$fromstr 	= array();
		$wherestr	= array();
		$orderstr 	= array();
		$conditions = array();

		/* WARNING : contains MOAQ (mother of all queries)	*
		 * now approaching spaghetti complexity  ;) 		*/


		$href_task = $ce->href_task ?  "&task=".$ce->href_task : "";
		$href_view = $ce->href_view ?  "&view=".$ce->href_view : "";
		$href_link = "";
		if($ce->slug_links){
			if($ce->title_alias){
				$href_link = "&".$ce->href_id."=', c.".$ce->id .",':',c.".$ce->title_alias ;
			}
			else{
				$href_link = "&".$ce->href_id."=', c.".$ce->id .",':',c.".$ce->title ;
			}
		}
		else{
			$href_link = "&".$ce->href_id."=', c.".$ce->id ;
		}
		$selstr[] 			= "c.".$ce->id." AS id";
		$selstr[] 			= "c.".$ce->title. " AS title";
		$selstr[] 			= ($ce->title_alias	? "c.".$ce->title_alias : "''" ) . " AS title_alias";
		$selstr[] 			= "c.".$ce->introtext. " AS introtext";
		$selstr[] 			= "CONCAT('index.php?option=".$ce->href_option.$href_view.$href_task.$href_link.") AS href";
		$selstr[] 			= ($ce->created		? "c.".$ce->created 	: "''" ) . " AS created";
		$selstr[] 			= ($ce->fulltext	? "c.".$ce->fulltext 	: "''" ) . " AS `fulltext`";
		$selstr[] 			= ($ce->images		? "c.".$ce->images 		: "''" ) . " AS images";

		if($ce->sec_table){
			$selstr[] 		= "sec.".$ce->sec_table_id." AS secid ";
			$selstr[] 		= "sec.".$ce->sec_table_title." AS section";
		}
		else{
			$selstr[] 		= "'".$ce->cat_section_filter."' AS section";
		}

		$fromstr[] 			= "#__".$ce->table." AS c";
		if($ce->cat_table)	$fromstr[] = "LEFT JOIN #__".$ce->cat_table." AS cat ON(c.".$ce->catid." = cat.".$ce->cat_table_id.") ";
		if($ce->sec_table)	$fromstr[] = "LEFT JOIN #__".$ce->sec_table." AS sec ON(c.".$ce->sectionid." = sec.".$ce->sec_table_id.") ";

		if($ce->state){
			if($params->get('search_archived')){
				$wherestr[] = "c.".$ce->state." <> '0'";
			}
			else{
				$wherestr[] = "c.".$ce->state." = '1'";
			}
		}
		if($ce->cat_table && $ce->cat_table_published)	$wherestr[] = "(cat.".$ce->cat_table_published." = '1' OR cat.".$ce->cat_table_published." IS NULL)";
		if($ce->sec_table && $ce->sec_table_published)	$wherestr[] = "(sec.".$ce->sec_table_published." = '1' OR sec.".$ce->sec_table_published." IS NULL)";
		if($ce->publish_up) $wherestr[] = "( c.".$ce->publish_up." = " . $database->Quote( $nullDate ) . " OR c.".$ce->publish_up." <= ".$database->Quote( $now )." )";
		if($ce->publish_down)$wherestr[]= "( c.".$ce->publish_down." = " . $database->Quote( $nullDate ) . " OR c.".$ce->publish_down." >= ".$database->Quote( $now )." )";
		// showing only one section , if navigating from "result_summary"
		$section_filter = "";
		if( ($show_section = JRequest::getVar('show_section','')) && $ce->sec_table_id){
			$show_section = $database->getEscaped($show_section);
			$wherestr[] = "sec.".$ce->sec_table_id." = '$show_section' ";
		}
		if(! $params->get('search_unauthorized') && $ce->access){
			$wherestr[] = "c.".$ce->access." <= '$aid' ";
		}

		switch($params->get('ordering_field')){
			case '1' :
				if($ce->title_alias) $ordering_field = $ce->title_alias;
				break;
			case '0' :
			default : $ordering_field = $ce->title;
			break;
		}

		switch($ordering){
			case 'category' :
				if(!$got_JoomFish){
					if($ce->sec_table_title) $orderstr[] = "sec." . $ce->sec_table_title;
					if($ce->cat_table_title) $orderstr[] = "cat." . $ce->cat_table_title;
				}
				break;
			case 'alpha':
				if(!$got_JoomFish){
					$orderstr[] = "c.$ordering_field";
				}
				break;
			// TODO rating sorting
			/*
			case 'rating':
				$orderstr = "ORDER BY r.rating_sum / r.rating_count DESC, r.rating_sum DESC ";
				break;
			*/
			case 'oldest':
				$orderstr[] = "c.".$ce->created." ASC";
				break;
			case 'newest':
			default:
				$orderstr[] = "c.".$ce->created." DESC";
				break;
		}

		if(!empty($tags) && !is_array($tags)){ // TAG SEARCH
			$search_pars['tagId'] = $tags ;
			$fromstr[] = "INNER JOIN #__custom_properties AS cp
				ON(c.".$ce->id." = cp.content_id)
				INNER JOIN #__custom_properties_values AS v
				ON (cp.value_id = v.id )";

			$wherestr[] = "v.id = '$tags' ";
			$wherestr[] = "cp.ref_table = '".$ce->table."' ";

		}
		elseif(!empty($tags) && is_array($tags)){
			$search_pars['tagId'] = $tags ;
			$i = 0;
			foreach($tags as $tag){
				$fromstr[] = "INNER JOIN #__custom_properties AS cp$i
					ON(c.".$ce->id." = cp$i.content_id)
					INNER JOIN #__custom_properties_values AS v$i
					ON (cp$i.value_id = v$i.id ) ";

				$wherestr[] = "v$i.id = '$tag' ";
				$wherestr[] = "cp$i.ref_table = '".$ce->table."' ";
				$i++;
			}
			if($exclude_id){
				$wherestr[] = "c.".$ce->id." not in ($exclude_id) ";
			}
		}
		else{ // STANDARD SEARCH
			$i= 0;
			foreach($_REQUEST as $key=>$field_values){
				if(strpos($key,'cp_') === 0){
					$field_name = $database->getEscaped(substr($key,3));

					if( ($field_type = $this->_getFieldType($field_name, $aid)) && $field_values != ""){
						$search_pars[$key] = $field_values;
						$wherestr[] = "cp$i.ref_table = '".$ce->table."'";

						$fromstr[] = "INNER JOIN #__custom_properties AS cp$i
							ON(c.".$ce->id." = cp$i.content_id)
							INNER JOIN #__custom_properties_values AS v$i
							ON (cp$i.value_id = v$i.id )
							INNER JOIN #__custom_properties_fields AS f$i
							ON (v$i.field_id = f$i.id ) ";

						$conditions = array();
						if($field_type == 'checkbox'){
							foreach($field_values as $value){
								$value = $database->getEscaped($value);
								$conditions[] = "(f$i.name = '$field_name' AND v$i.name = '$value' AND f$i.published='1' AND f$i.access <= '$aid' )";
							}
							$wherestr[] = "(". implode(" OR " , $conditions). ")";
						}
						elseif($field_type == 'select'){
							$field_values = $database->getEscaped($field_values);
							$wherestr[] = "(f$i.name = '$field_name' AND v$i.name = '$field_values'  AND f$i.published='1' AND f$i.access <= '$aid' ) ";
						}
						elseif($field_type == 'text'){
							// 1st find the tags that match the text
							if( $tags = $this->_getTagsByText($field_name, $field_values, $aid, $got_JoomFish )){
								$j = 0;
								$conditions = array();
								foreach($tags as $tag){
									$fromstr[]	= "INNER JOIN #__custom_properties AS cp_".$i."_".$j." ON (c.".$ce->id." = cp_".$i."_".$j.".content_id) " ;
									$conditions[] = "cp_".$i."_".$j.".value_id = '$tag' " ;
									$j++;
								}
								$wherestr[] = "(". implode(" OR " , $conditions). ")";
							}
							else{
								array_push($this->errors, "no match");
								return false;
							}
						}
					}
					elseif($field_name == "text_search" && $field_values != ""){
						$field_values = JFilterInput::clean($field_values);
						if(strlen(trim($field_values)) < 3){
							array_push($this->errors, "text short");
							return false;
						}

						$searchword 		= $field_values;
						$search_pars[$key] 	= $searchword;
						$this->searchword 	= $searchword ;
						$searchword 		= $database->getEscaped($searchword);

						$fulltext = $ce->fulltext ? ", c.".$ce->fulltext : '';
						// translation of search text with JoomFish
						if($got_JoomFish){
							$fromstr[] = "LEFT JOIN #__jf_content as jfc ON jfc.reference_id = c.".$ce->id .
								" LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id ";

							$wherestr[] = "( (jfc.value like '%$searchword%' AND jfl.code = '$language') \n".
								"\n OR CONCAT(c.".$ce->title.",' ', c.".$ce->introtext.",' '".$fulltext.") LIKE '%$searchword%' ) \n";
						}
						else{
							$wherestr[] = "\n CONCAT(c.".$ce->title.",' ', c.".$ce->introtext.",' '".$fulltext.") LIKE '%$searchword%' \n";
						}
					}
					else{
						continue;
					}
					$i++;
				}
			}
		}

		// Filter by section
		// component section scope
		if($ce->sec_table){
			$global_sections = array();
			if( ($search_sections = $params->get('search_sections')) != "" ){
				$global_sections = split(',',$search_sections);
			}
			// module section scope
			$module_sections = array();
			if( ($search_sections = preg_replace( '/[^0-9\,]/', '', JRequest::getVar("bind_to_section")) ) != "" ) {
				$module_sections = split(',',$search_sections);
			}

			if(count($global_sections) and  !count($module_sections) ){
				$section_scope = implode(',', $global_sections ) ;
			}
			elseif(!count($global_sections) and  count($module_sections) ){
				$section_scope = implode(',', $module_sections ) ;
			}
			elseif(count($global_sections) and count($module_sections) ){
				$section_scope = implode(',', array_intersect($global_sections , $module_sections) );
				// if no intersection , global rules
				if(empty($section_scope )){
					$section_scope = implode(',', $global_sections ) ;
				}
			}

			if(!empty($section_scope )){
				$wherestr[] = "sec.".$ce->sec_table_id." IN ($section_scope) ";
			}
		}

		/* TODO rating
		if($ordering == 'rating'){
			$fromstr .= "LEFT JOIN #__content_rating AS r on(c.id = r.content_id) ";
		}
		*/

		$limitstr = "";
		if($limit > 0 ){
			$limitstr = " LIMIT $limit ";
		}

		$selstr 	= join($selstr, ",");
		$fromstr 	= join($fromstr, " \n");
		$wherestr 	= join($wherestr, "\n AND ");
		$orderstr	= join($orderstr, ",");

		$this->fromstr = $fromstr;
		$this->wherestr = $wherestr;
		// eventually putting the query together ...
		$query = "SELECT DISTINCT " . $selstr . "\n" .
			" FROM " . $fromstr ."\n" .
			" WHERE ". $wherestr ."\n" .
			(!empty($orderstr) ? " ORDER BY " .$orderstr : "") .
			 $limitstr ;

		if(!empty($tags) && !is_array($tags) && $params->get('show_tags')){
			$this->tagname = getTagNameById($tags, $params);
			$search_pars['tagId'] = $tags;
		}

		if(! count($search_pars) > 0  ) {
			array_push($this->errors, "no pars");
			return false;
		}
		if(!empty($section_scope )){
			$search_pars['bind_to_section'] = $section_scope;
		}
//TODO cleanup the cose print "<pre><tt>$query</tt></pre>";


		$database->setQuery("$query");
		$rows = $database->loadObjectList();

		if($rows === null ){
			array_push($this->errors, "db error");
			return false;
		}

		/* Tricky matter: sorting alpha with joomFish.
		* Unreliable with SQL only, because you hardly get to know
		* which table you are reading from.
		* In this case we sort with Joomla API instead.
		*/

		if($got_JoomFish && $ordering == 'alpha'){
			JArrayHelper::sortObjects($rows, $ordering_field);
		}
		elseif($got_JoomFish && $ordering == 'category'){
			JArrayHelper::sortObjects($rows, 'section');
		}

		return $rows;
	}

	/** This function makes sure that the given search filed is a valid one
	 * i.e. it is one CP field
	 * @param string $field_name name of the field
	 * @param integer $aid group id of current user. Note: not all parameters have same visibility
	 * @return string false if it's not a valid field. Otherwise it returns the filed type.
	 */
	function _getFieldType($field_name, $aid){

		$database 	= JFactory::getDBO();
		$result 	= null;

		$query ="SELECT f.id, f.name, f.field_type
		FROM #__custom_properties_fields f
		WHERE access <= '$aid'
		AND name = '$field_name' ";

		$database->setQuery($query);
		$result = $database->loadObject();

		if($result) {
			return $result->field_type;
		}
		else{
			return false;
		}
	}

	/** This function returns all values id (tags)  whose label matches (LIKE) a given string
	 * @param string $tag_name Name of the tag
	 * @param string $text Text to match field labels against
	 * @param integer $aid Group id of current user. Default 0
	 * @param bool $got_JoomFish Is Joomfish installed ? Default false
	 * @return array of tag id(s) or null if non labels match
	 */
	 function _getTagsByText($tag_name, $text, $aid = 0, $got_JoomFish = false){

	 	if(empty($tag_name) || empty($text)) return false;

		global $mainframe;
		$database 	= JFactory::getDBO();
		$language 	= $mainframe->getCfg('language');

	 	$result = null;

	 	if($got_JoomFish){
	 		$query = "SELECT DISTINCT v.id " .
	 			"FROM #__custom_properties_fields AS f " .
				"INNER JOIN #__custom_properties_values as v ON f.id = v.field_id " .
	 			"LEFT JOIN #__jf_content as jfc ON jfc.reference_id = v.id " .
	 			"LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id " .
	 			"WHERE f.access <= '$aid' " .
	 			"AND f.name = '$tag_name' " .
	 			"AND ( ( jfc.value LIKE '$text%' AND jfl.code = '$language') " .
	 			" OR v.label LIKE '$text%' ) ";
	 	}
	 	else{
	 		$query = "SELECT v.id " .
	 			"FROM #__custom_properties_fields as f " .
	 			"INNER JOIN #__custom_properties_values as v " .
	 			"ON (f.id = v.field_id) " .
	 			"WHERE f.access <= '$aid' " .
	 			"AND f.name = '$tag_name' " .
	 			"AND v.label LIKE '$text%' ";
	 	}

		$database->setQuery($query);
		$result = $database->loadResultArray();

		return $result;
	 }

}

/**
 * simple class for storing result summary rows
 *
 */
class resultSummaryRow{
	/**
	 * Content element label
	 *
	 * @var string content element label
	 */
	 var $ce_label;
	/**
	 * Content element link
	 *
	 * @var string content element label
	 */
	 var $ce_link;
	/**
	 * Section Label
	 *
	 * @var string label to show
	 */
	var $section;
	/**
	 * Link
	 *
	 * @var string url to assign to the label
	 */
	 var $sec_link;
	/**
	 * Count
	 *
	 * @var int number of matching items
	 */
	var $count;
	/**
	 * Selected
	 *
	 * @var bool currently selected ?
	 */
	var $selected;

	/**
	 * inizialize a result summary row
	 */
	function resultSummaryRow($ce_label, $ce_link="", $section, $sec_link, $count, $selected=0){

		$this->ce_label	= $ce_label;
	 	$this->ce_link	= $ce_link;
	 	$this->section	= $section;
	 	$this->sec_link	= $sec_link;
	 	$this->count 	= $count;
	 	$this->selected	= $selected;

	}
}

/**
 * Method to return the tag name given the tag id
 *
 * @param string tag id
 * @param object component params
 * @access private
 * @returns string
 */
function getTagNameById($tagId, $params){

	$database = JFactory::getDBO();

	$query = "SELECT f.id as fid, f.label as name, v.id as vid, v.label
		FROM #__custom_properties_fields AS f
		INNER JOIN #__custom_properties_values AS v
			ON (f.id = v.field_id )
		WHERE v.id = '$tagId'
		LIMIT 1";

	$database->setQuery("$query");
	$result = $database->loadObject();

	if($result){
		$tagname = $params->get('show_tag_name') ? $result->name.":".$result->label : $result->label ;
		return $tagname;
	}
}