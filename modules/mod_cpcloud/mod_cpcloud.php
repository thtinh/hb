<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Modu cp_cloud
* @version 1.98.3.3
* @revision $Revision: 1.8 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

global $cp_config , $Itemid;
$database 	= & JFactory::getDBO();
$user 		= & JFactory::getUser();

// make sure component is installed
if (!file_exists(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_customproperties'.DS.'admin.customproperties.php')){
  echo "<span class=\"alert\">Fatal Error: the component <b>com_customproperties</b> is not installed. "
  . "Get it at <a href=\"http://www.solidsystem.it\">www.solidsystem.it</a></span>";
  return;
}

require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_customproperties'.DS.'cp_config.php');

$ce_file = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_customproperties'.DS.'contentelement.class.php';
if (!file_exists($ce_file)){
  echo "<span class=\"alert\">Fatal Error: wrong version of <b>com_customproperties</b>. "
  . "Please upgrade at <a href=\"http://www.solidsystem.it\">www.solidsystem.it</a></span>";
  return;
}
else{
	require_once($ce_file);
}

$moduleclass_sfx		= $params->def('moduleclass_sfx','');
$tags_sorting			= $params->def('tags_sorting','0');
$use_cpcloud_stylesheet	= $params->def('use_cpcloud_stylesheet','1');
$bind_to_section		= preg_replace( '/[^0-9\,]/', '', 	$params->def('bind_to_section','') );
$bind_to_ce				= $params->def('bind_to_ce','');
$force_itemid			= preg_replace( '/[^0-9]/', '', 	$params->def('itemid','') );
$threshold				= preg_replace( '/[^0-9]/', '', 	$params->def('tags_threshold','') );

if($bind_to_ce != ""){
	if(!$content_elements[] = getContentElementByName($bind_to_ce)){
		$content_elements[] = getDefaultContentElement();
	}
}
else{
	$content_elements = getInstalledContentElements();
}

if($use_cpcloud_stylesheet ){
  $document = & JFactory::getDocument();
  $document->addStyleSheet(JURI::base(). '/modules/mod_cpcloud/css/cpcloud.css');
}

$rows 		= array();
$tot_rows	= 0;
$tot_tags	= 0;
foreach($content_elements as $ce){
	$fromstr 	= array();
	$wherestr 	= array();

	$selstr 	= "v.id AS vid, v.label as value, f.id AS fid , f.label AS field, COUNT(*) AS cnt";
	$fromstr[]	= "#__custom_properties AS cp";
	$fromstr[]	= "INNER JOIN #__custom_properties_values AS v ON (cp.value_id = v.id)";
	$fromstr[]	= "INNER JOIN #__custom_properties_fields AS f ON (cp.field_id = f.id )";
	$fromstr[]	= "INNER JOIN #__".$ce->table." AS c ON (cp.content_id = c.".$ce->id.")";
	$wherestr[] = "cp.ref_table = '".$ce->table."'";
	$wherestr[] = "f.published = 1";
  	$wherestr[] = "f.access <= '" . $user->get('gid') . "'";

	if($bind_to_section != '' ){
		if($ce->sectionid){
			$wherestr[] = "c." . $ce->sectionid . " IN ($bind_to_section)";
		}
		else{
			$wherestr[] = '0';
		}
	}
	$groupstr	= "v.label ";
	$fromstr 	= join(' ', $fromstr);
	$wherestr	= join(' AND ', $wherestr);

	$query = "SELECT $selstr FROM $fromstr WHERE $wherestr GROUP BY $groupstr";

	$database->setQuery($query);
	if($buffer = $database->loadObjectList()){
		$rows = array_merge($rows, $buffer);
	}

}

$tot_rows = count($rows);
if($tot_rows < 1 ){
  echo "<div class=\"cpcloud'.$moduleclass_sfx.'\">
    Tags not found
  </div>";
  return;
}

if(count($content_elements) > 1 && $tot_rows > 1){
	//if I got more that one content elements , I need to sum
	// the count of same tags
	$rows = JArrayHelper::sortObjects($rows, 'vid');
	//array sorted . I sum counts id two adjacent rows ahve same vid
	for($i = 1 ; $i < $tot_rows; $i++){
		if($rows[$i]->vid == $rows[$i-1]->vid){
			$rows[$i]->cnt+=$rows[$i-1]->cnt;
			unset($rows[$i-1]);
		}
	}
}

// find min and max value
foreach($rows as $row){
	if($row->cnt > $threshold){
		$temp[] = $row->cnt ;
	}
}
$max = max($temp);
$min = min($temp);

// number of classes
$num_of_classes	= 5;
$distance = $max  - $min  + 0.5 ; // adding a quid so that max value does not fall on upper limit of scale
$class_width = $distance / $num_of_classes ;

switch($tags_sorting){
	case 1:
		JArrayHelper::sortObjects($rows, 'value');
		break;
	case 2:
		shuffle($rows);
		break;
	default:
}

$bind_to_ce_url	 = $bind_to_ce != "" ? 		"&content_element=".urlencode($ce->name) : "";
$bind_to_sec_url = $bind_to_section != "" ? "&bind_to_section=".urlencode($bind_to_section) : "";

$itemid_url = "";
if($cp_config['use_itemid'] == 1){
	$itemid_url = "&Itemid=".$Itemid ;
}
if($force_itemid){
	$itemid_url = "&Itemid=".$force_itemid ;
}


?>
<div class="cpcloud<?php echo $moduleclass_sfx;?>">
<ul class="cpcloud<?php echo $moduleclass_sfx;?>">
<?php
foreach ($rows as $row) {
	$size = intval(($row->cnt - $min) / $class_width) + 1;
	if($row->cnt < $threshold) continue;
	/* url format : tagID is language independend.
	 * tagName : can have issues with localized sites but can be processed by SEF components */
	if ($cp_config['url_format'] == '0') { // tagId
		$link = JRoute::_("index.php?option=com_customproperties&task=tag".$bind_to_ce_url.$bind_to_sec_url."&tagId=" . $row->vid.$itemid_url );
	} else {	//tagName
		$link = JRoute::_("index.php?option=com_customproperties&task=tag".$bind_to_ce_url.$bind_to_sec_url."&tagName=" . urlencode($row->field) . ":" . urlencode($row->value).$itemid_url);
	}
	echo "<li><a href=\"$link\" class=\"cpcloud$size\">" . htmlspecialchars($row->value) ."</a></li>\n";
}

?>
</ul>
</div>

