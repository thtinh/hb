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

defined( '_JEXEC' ) or die( 'Restricted access' );

/** connects a generic content item to custom properties fields */
class cpContentElement{

	/* content element name */
	var $name = null;
	/* content element label, used for display purposes (article, books , wines , whatever) */
	var $label = null;
	/* active / not active */
	var $active = null;
	/* $ce_order = influence how the retrieved items will be listed. Lower ordering are displayed before higher ordering */
	var $ce_ordering = null;
	/* XML filename */
	var $xml = null;
	/* table (without prefix) */
	var $table = null;
	/* content item unique id */
	var $id = null;
	/* title */
	var $title = null;
	/* title alias */
	var $title_alias = null;
	/* section id */
	var $sectionid = null;
	/* category id */
	var $catid = null;
	/* create date - used for sorting */
	var $created = null;
	/* introtext */
	var $introtext = null;
	/* fulltext */
	var $fulltext = null;
	/* state 0 = not published, 1 = published , 2 = archived */
	var $state = null ;
	/* access 0 = public, 1 = registered, 2 = special */
	var $access = null;
	/* publish_up */
	var $publish_up = null;
	/* publish_down */
	var $publish_down = null;
	/* images */
	var $images = null;
	/* images directory respective to site URL e.g. images/stories - no trailing slash */
	var $images_dir = null;
	/* ordering field */
	var $ordering = null;
	/* href option part used to open the content item	 */
	var $href_option = null;
	/* href view part used to open the content item 	 */
	var $href_view = null;
	/* href task part used to open the content item 	 */
	var $href_task = null;
	/* id as use in url query string (often id )	 */
	var $href_id = null;
	/* slug_links if set to 1 it will build "slug"  link
	 * eg:  id:article_alias
	 */
	var $slug_links = null;


	/* mapping to external section table */
	/* section table name (without prefix)*/
	var $sec_table = null;
	/* section table id field */
	var $sec_table_id = null;
	/* section table title / description field */
	var $sec_table_title;
	/* section table published field */
	var $sec_table_published;

	/* mapping to external category table */
	/* category table name (without prefix)*/
	var $cat_table = null;
	/* category table id field */
	var $cat_table_id = null;
	/* category table title / description field */
	var $cat_table_title = null;
	/* category table published field */
	var $cat_table_published = null;
	/* category table section id field */
	var $cat_parent_section_field = null;
	/* category parent section table*/
	var $cat_parent_section_table	= null;
	/* section filter for categories, to be used if section table is not used  */
	var $cat_section_filter	= null;

	/** class constructor
	 * @param xmlfile $xml path to the XML file
	 * @param boolean $load_inactive load inactive content elements, default false
	 * @return the content element object or the default 'content' CE if XML file is not given
	 * */
	function cpContentElement($xml=null, $load_inactive = false){

		if($xml == null) {
			$this->loadDefault();
			return;
		}
		else{
			//XML library
			require_once( JPATH_ROOT.DS."includes".DS."domit".DS."xml_domit_lite_include.php" );
			$xmlDoc =& new DOMIT_lite_Document();
			$xmlDoc->resolveErrors( true );

			if ($xmlDoc->loadXML( $xml, false, true )) {
				$element =& $xmlDoc->documentElement;
				if ($element->getTagName() == 'contentelement') {

					$this->xml = $xml;

					if($element->hasAttribute("active")){
						// content element is not active
						if($element->getAttribute("active") == '0'){
							$this->active = '0';
							if(!$load_inactive) return;
						}
						if($element->getAttribute("active") == '1'){
							$this->active = '1';
						}
					}

					if($element->hasAttribute("ordering")){
						$this->ce_ordering = $element->getAttribute("ordering");
					}
					else{
						$this->ce_ordering = 999;
					}
					//TODO check required fields
					//TODO author and such
					//$info =& $element->getElementsByPath('author');

					$info =& $element->getElementsByTagName('name');
					if($info != null){
						$currNode 	=& $info->item(0);
						$table_name = trim($currNode->getText());
						if($table_name != null){
							$this->name = $table_name;
						}
						else{
							$this->loadDefault();
							return;
						}

					}
					else{
						$this->loadDefault();
						return;
					}

					// content table name
					$table =& $element->getElementsByPath('content_table/table');
					if($table != null){
						$currNode 	=& $table->item(0);
						$table_name = trim($currNode->getText());
						if($table_name != null){
							$this->table = $table_name;
						}
						else{
							$this->loadDefault();
							return;
						}

						// fields
						$table =& $element->getElementsByPath('content_table/field');
						if ($table != null) {
							$total = $table->getLength();
							for ($i = 0; $i < $total; $i++) {
								$currNode 	=& $table->item($i);
								$prop_name 	= trim($currNode->getAttribute('name'));
								$prop_val 	= trim($currNode->getText());
								if($prop_val != ""){
									$this->$prop_name = $prop_val;
								}
							}
						}
						else{
							$this->loadDefault();
							return;
						}
					}
					else{
						$this->loadDefault();
						return;
					}

					// CATEGORIES table name - optional
					$table =& $element->getElementsByPath('category/table');
					if($table != null){
						$currNode =& $table->item(0);
						$table_name = trim($currNode->getText());
						if($table_name != null){
							$this->cat_table = $table_name;
						}

						// fields
						$table =& $element->getElementsByPath('category/field');
						if ($table != null) {
							$total = $table->getLength();
							for ($i = 0; $i < $total; $i++) {
								$currNode 	=& $table->item($i);
								$prop_name 	= trim($currNode->getAttribute('name'));
								$prop_val 	= trim($currNode->getText());
								if($prop_val != ""){
									$this->$prop_name = $prop_val;
								}
							}
						}
					}


					// SECTIONS table name - optional
					$table =& $element->getElementsByPath('section/table');
					if($table != null){
						$currNode =& $table->item(0);
						$table_name = trim($currNode->getText());
						if($table_name != null){
							$this->sec_table = $table_name;
						}

						// fields
						$table =& $element->getElementsByPath('section/field');
						if ($table != null) {
							$total = $table->getLength();
							for ($i = 0; $i < $total; $i++) {
								$currNode 	=& $table->item($i);
								$prop_name 	= trim($currNode->getAttribute('name'));
								$prop_val 	= trim($currNode->getText());
								if($prop_val != ""){
									$this->$prop_name = $prop_val;
								}
							}
						}
					}
				}
				else{
					$this->loadDefault();
					return;
				}
			}
			else{
				$this->loadDefault();
				return;
			}
		}
	}

	/** load the default content item connector */
	function loadDefault(){
		$this->name			= 'content';
		$this->label		= 'content';
		$this->active		= '1';
		$this->ce_ordering	= '0';
		$this->xml			= "";
		$this->table 		= 'content';
		$this->id			= 'id';
		$this->title		= 'title';
		$this->title_alias	= 'alias';
		$this->sectionid	= 'sectionid';
		$this->catid		= 'catid';
		$this->created		= 'created';
		$this->introtext	= 'introtext';
		$this->fulltext		= 'fulltext';
		$this->state		= 'state';
		$this->access		= 'access';
		$this->publish_up	= 'publish_up';
		$this->publish_down	= 'publish_down';
		$this->images		= 'images';
		$this->images_dir	= 'images/stories';
		$this->ordering		= 'ordering';
		$this->href_option	= 'com_content';
		$this->href_view	= 'article';
		$this->href_task	=  null;
		$this->href_id		= 'id';
		$this->slug_links	= '1';

		$this->sec_table			= 'sections';
		$this->sec_table_id			= 'id';
		$this->sec_table_title 		= 'title';
		$this->sec_table_published 	= 'published';

		$this->cat_table			= 'categories';
		$this->cat_table_id			= 'id';
		$this->cat_table_title 		= 'title';
		$this->cat_table_published 	= 'published';
		$this->cat_parent_section_field	= 'section';
		$this->cat_parent_section_table = 'section';
	}
}
/** function that returns all installed content elements
 * @param boolean $load_inactive load inactive content elements, default false
 * @return array associative array of cpContentElements. Keys of the array ac Content Element name
 * */
function getInstalledContentElements($load_inactive=false){
	// TODO chaching
	$content_elements =&  loadContentElements($load_inactive);
	return $content_elements;
}
/**
 * Loading of related XML files
 * @param boolean $load_inactive load inactive content elements, default false
 * @return array of objects returns an array of CP Content Elements
 *
*/
function loadContentElements($load_inactive=false) {

	$ce_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_customproperties'.DS."contentelements";
	// Try to find the XML file
	$filesindir = JFolder::files($ce_path ,".xml");

	$result = array();
	if(count($filesindir) > 0){
		$content_elements = array();
		foreach($filesindir as $file)
		{
			$ce =& new cpContentElement($ce_path."/".$file, $load_inactive);
			if(!empty($ce->name)) {
				$result[] =& $ce;
			}
		}
		// sort by ce_ordering
		JArrayHelper::sortObjects($result,'ce_ordering');
	}
	else{
		// return the load the standard content element
		$result['content'] =& new cpContentElement();
	}
	return $result;
}
/** function that returns all available content elements in dir 'samplece'
 * @return array array of content elements names
 * */
function getAvailableContentElements(){

	$ce_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_customproperties'.DS."samplece";
	// Try to find the XML file
	$filesindir = str_ireplace('.xml', '', JFolder::files($ce_path ,".xml"));

	return $filesindir;

}

/** Loads a cpContentElement by name
 * @param string $ce_name Name of the cpContentElement
 * @param boolean $load_inactive load inactive content elements, default false
 * @return cpContentElement or false if it does nor exists
 */
function getContentElementByName($ce_name , $load_inactive = false){
	// TODO cacheing!
	$content_elements = getInstalledContentElements($load_inactive);
	foreach($content_elements as $ce){
		if($ce->name == "$ce_name") return $ce;
	}
	return false;
}
/** Loads a cpContentElement by option (com_content, com_docman, ...)
 * @param string $option option (com_content, com_docman, ...)
 * @param boolean $load_inactive load inactive content elements, default false
 * @return cpContentElement or false if it does nor exists
 */
function getContentElementByOption($option , $load_inactive = false){
	// TODO cacheing!
	$content_elements = getInstalledContentElements($load_inactive);
	foreach($content_elements as $ce){
		if($ce->href_option == "$option") return $ce;
	}
	return false;
}
/** Loads the default content element (content)
 * @return cpContentElement the default content elements
 */
function getDefaultContentElement(){
	return new ContentElement();
}