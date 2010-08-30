<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.7 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die('Restricted access');

global $mainframe;
$params         = $this->params;
$errors         = $this->errors;
$ordering       = $this->ordering;

$list = array();
$orders = array(
  JHTML::_('select.option', 'newest', JText::_('Newest First')),
  JHTML::_('select.option', 'oldest', JText::_('Oldest First')),
  JHTML::_('select.option', 'alpha',  JText::_('Alphabetical')),
  JHTML::_('select.option', 'category', JText::_('Section/Category'))
);
$lists['ordering'] = JHTML::_('select.genericlist', $orders, 'ordering', 'class="inputbox" onchange="this.form.submit()"', 'value', 'text', $ordering);

$document =& JFactory::getDocument();
$task="show";
if(!empty($tagname) ) {
	$task = "tag";
	// add tag name to page title
	$document->setTitle( $tagname );

	// ... and to meta keywords
	$keywords =  split(',', $document->getMetaData('keywords'));
	if(!in_array($tagname, $keywords)) {
		$keywords[] = htmlspecialchars($tagname);
		$document->setMetaData('keywords', implode(',',$keywords) );
	}
}
//TODO titolo della pagina quando si ricerca senza tag
if($params->get('use_cp_css')){
	$document->addStyleSheet(JURI::Base().'/components/com_customproperties/css/customproperties.css');
}

if ( $params->get( 'show_page_title' ) ) { ?>
<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
	<?php echo $params->get( 'header' ); ?>
</div>
<?php } ?>


<?php if( !empty($errors) ) { ?>
<table class="searchintro<?php echo $params->get( 'pageclass_sfx' ); ?>">
  <?php foreach($errors as $error){ ?>
	<tr>
		<td>
			<?php echo searchError($error); ?>
		</td>
	</tr>
  <?php }?>
</table>
<?php
return;
}


if($params->get('show_result_summary')){
	$result_summary = $this->result_summary;
}
$results        = $this->data;
$pageNav        = $this->pagination;
$total          = $pageNav->total;
$limit          = $pageNav->limit;
$search_pars    = $this->search_pars;
$searchword     = empty($search_pars['cp_text_search']) ? '' : $search_pars['cp_text_search'];
$tagname        = $this->tagname;
$bind_to_section = JRequest::getVar('bind_to_section', '');
?>

<form id="searchhead" action="index.php" method="get" >
<input type="hidden" name="option" value="com_customproperties"/>
<input type="hidden" name="task" value="<?php echo $task;?>"/>
<input type="hidden" name="limit" value="<?php echo $limit;?>"/>
<input type="hidden" name="bind_to_section" value="<?php echo $bind_to_section ?>"/>
<?php

foreach($search_pars as $key => $value){
  if(is_array($value)){
    foreach($value as $v){
      echo "<input type=\"hidden\" name=\"$key".'[]'."\" value=\"$v\"/>\n";
    }
  }
  else{
    echo "<input type=\"hidden\" name=\"$key\" value=\"$value\"/>\n";
  }
}

?>
<table width="100%" class="search_summary">
	<?php if($params->get('search_header_tl') || $params->get('search_header_tr')) : ?>
	<tr>
		<td width="50%"><?php echo renderSearchSummaryElement($params->get('search_header_tl'), $searchword, $tagname, $total, $lists['ordering']) ?></td>
		<td width="50%"><?php echo renderSearchSummaryElement($params->get('search_header_tr'), $searchword, $tagname, $total, $lists['ordering']) ?></td>
	</tr>
	<?php endif; ?>
	<?php if($params->get('search_header_bl') || $params->get('search_header_br')) : ?>
	<tr>
		<td width="50%"><?php echo renderSearchSummaryElement($params->get('search_header_bl'), $searchword, $tagname, $total, $lists['ordering']) ?></td>
		<td width="50%"><?php echo renderSearchSummaryElement($params->get('search_header_br'), $searchword, $tagname, $total, $lists['ordering']) ?></td>
	</tr>
	<?php endif; ?>
</table>
</form>

<?php if($params->get( 'show_result_summary' ) ){

	echo "<ul class=\"result_summary\">\n";

	$linked_result_summary = $params->get('linked_result_summary');
	if($linked_result_summary){
		$class = $result_summary[0]->selected ? "class=\"active\"" : "";
		echo "<li $class><a href=\"".JRoute::_($result_summary[0]->sec_link)."\">".$result_summary[0]->section."</a></li>\n";
	}
	unset($result_summary[0]);

	foreach($result_summary as $ce_results){
		foreach($ce_results as $ce_name => $row){
			$class = $row->selected ? "class=\"active\"" : "";
			echo "<li $class>\n";
			if($params->get('show_content_element')){
				if($linked_result_summary) {
					echo "<a href=\"".JRoute::_($row->ce_link)."\">";
				}
				echo htmlspecialchars($row->ce_label).":";
				if($linked_result_summary) {
					echo "</a>\n";
				}
			}
			if($linked_result_summary) {
				echo "<a href=\"".JRoute::_($row->sec_link)."\">";
			}
			echo htmlspecialchars($row->section);
			if($linked_result_summary) {
				echo "</a>\n";
			}
			echo " (".$row->count.") ";
			echo "</li>\n";
		}
	}

	echo "</ul>\n";
}

$text_length    = $params->get('text_length');
$show_section   = $params->get('show_section');
$show_ce_label  = $params->get('show_content_element_label');
$show_tags      = $params->get('show_tags');
$linked_tags    = $params->get('linked_tags');
$show_tag_name  = $params->get('show_tag_name');
$view = "";
switch ($params->get('view')) {
	case 0 :
		$view = "title";
		break;
	case 1 :
		$view = "intro";
		break;
	case 2 :
	default :
		$view = "fullintro";
		break;
}

echo"
<table class=\"contentpaneopen".$params->get( 'pageclass_sfx' )."\">
	<tr>
		<td>\n";

// filter by content element type
$content_elements = array ();
if ($content_element = JRequest::getVar('content_element','')) {
	if ($ce = getContentElementByName($content_element)) {
		$content_elements[] = $ce;
	}
}
// I didn't get any valid content element, get all avaible
if (!$content_elements) {
	$content_elements = getInstalledContentElements();
}

foreach ($content_elements as $ce) {

	if (!empty ($results[$ce->name])){

		if($show_ce_label) {
			echo "<div class=\"cp_ce_label\">".$ce->label."</div>";
		}

		foreach($results[$ce->name] as $ce_name => $row){

			if ($row->created) {
				$created = JHTML::Date ($row->created);
			} else {
				$created = '';
			}

			echo "<div class=\"cp_result\">\n";

			switch($view){
				case 'title' :
					showTitle( $row, $params );
					break;
				case 'fullintro':
					showTitle( $row, $params );
					showFullIntro($ce, $row, $searchword, $params );
					break;
				case 'intro' :
				default:
					showTitle($row, $params );
					showIntro($row, $searchword, $params );
					break;
			}

			if($show_tags) echo showTags($ce, $row->id, $params);

			if ($params->get('show_create_date') ) {
				echo "<div class=\"cp_create_date\">$created</div>\n";
			}

			echo "</div>\n";
		}

	}

}
echo "
		</td>
	</tr>\n";

if($total > $limit ) { // pagination
echo"
  <tr>
    <td>
    <table width=\"100%\" class=\"search_pagination\">
      <tr>
        <td>"
          . $pageNav->getPagesLinks().
        "</td>
      </tr>
      </table>
    </td>
  </tr>\n";
}

echo "</table>\n";
