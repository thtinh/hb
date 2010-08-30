<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Mod cp_search
* @version 1.98
* @revision $Revision: 1.3.2.5 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

global $Itemid;

$database 	= & JFactory::getDBO();
$user 		= & JFactory::getUser();
$module_id 	= $module->id;

// make sure component is installed
if (!file_exists(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_customproperties' . DS . 'admin.customproperties.php')) {
	echo "<span class=\"alert\">Fatal Error: the component <b>com_customproperties</b> is not installed. " . "Get it at <a href=\"http://www.solidsystem.it\">www.solidsystem.it</a></span>";
	return;
}

$moduleclass_sfx 		= $params->def('moduleclass_sfx', '');
$use_cpsearch_stylesheet= $params->def('use_cpsearch_stylesheet','1');
$bind_to_section		= preg_replace( '/[^0-9\,]/', '', 	$params->def('bind_to_section','') );
$force_itemid			= preg_replace( '/[^0-9]/', '', 	$params->def('itemid','') );
$bind_to_ce				= $params->def('bind_to_ce','') ;
$checkbox_label_pos 	= $params->def('checkbox_label_pos', 'left');
$show_checkbox_fieldname= $params->def('show_checkbox_fieldname', '0');
$select_label_pos 		= $params->def('select_label_pos', 'inside');
$textfield_label_pos 	= $params->def('textfield_label_pos','before');
$text_search 			= $params->def('text_search', '0');
$layout 				= $params->def('layout_type', '0');
$auto_submit 			= $params->def('auto_submit', '0');
$text 					= $params->def('text', JText::_('search').'...');

if($use_cpsearch_stylesheet ){
  $document = & JFactory::getDocument();
  $document->addStyleSheet(JURI::base(). '/modules/mod_cpsearch/css/cpsearch.css');
}

require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_customproperties'.DS.'cp_config.php');
$itemid_url = "";
if($cp_config['use_itemid'] == 1){
	$itemid_url = $Itemid ;
}
if($force_itemid){
	$itemid_url = $force_itemid ;
}


$searched_text = JRequest::getVar('cp_text_search');

$onsubmit = "";
if ($text_search) {
	$onsubmit = "onsubmit=\"if(this.cp_text_search.value == '$text') this.cp_text_search.value = '';\"";
}

if ($searched_text != "") {
	$text = $searched_text;
}

$auto_submit_function = "";
if ($auto_submit) {
	$auto_submit_function = "cpSubmitForm$module_id();";
}

// retrieve fields relevant to this module or to all modules
$module_id = $module->id;
$query = "SELECT *
  FROM #__custom_properties_fields
  WHERE published = 1
  AND access <= '" . $user->get('gid') . "'
  AND ( FIND_IN_SET('$module_id',modules) OR modules = '' OR modules IS NULL )
  ORDER BY ordering ";

$database->setQuery($query);
$fields = $database->loadObjectList();
$count = count($fields);

if ($auto_submit) {
	echo<<<EOD
	<script type="text/javascript">
	function cpSubmitForm$module_id(){
		if(cpform = document.getElementById('cpsearchform$module_id')){
			if(cpform.cp_text_search){
				if(cpform.cp_text_search.value == '$text'){
					cpform.cp_text_search.value = '';
				}
				else{
					'';
				}
			}
			cpform.submit();
		}
	}
	</script>
EOD;
}
?>
<div class="cpsearch">
<form class="searchform" action="index.php" method="get" <?php echo $onsubmit?> id="cpsearchform<?php echo $module_id;?>">
<input type="hidden" name="option" value="com_customproperties"/>
<input type="hidden" name="view" value="show"/>
<input type="hidden" name="task" value="show"/>
<?php
if($itemid_url != ""){
	echo "<input type=\"hidden\" name=\"Itemid\" value=\"$itemid_url\"/>\n";
}
if($bind_to_section != ""){
	echo "<input type=\"hidden\" name=\"bind_to_section\" value=\"$bind_to_section\"/>\n";
}
if($bind_to_ce != ""){
	echo "<input type=\"hidden\" name=\"content_element\" value=\"$bind_to_ce\"/>\n";
}


echo $layout ? "<div>\n" : "<table>\n";
if ($count > 0) {

	foreach ($fields as $field) {
		$query = "SELECT *
				    FROM #__custom_properties_values
				    WHERE field_id = '" . $field->id . "'
				    ORDER by ordering ";

		$database->setQuery($query);
		$values = $database->loadObjectList();
		$values_count = count($values);

		if (empty ($values)) {
			echo $layout ? "<div>\n" : "<tr><td colspan=\"2\">\n";
			echo JText::sprintf('CP_ERR_NONVAL', $field->label);
			echo $layout ? "</div>\n" : "</td></tr>\n";
			continue;
		}

		$selected_field = JRequest::getVar('cp_' . $field->name, '');
		$class = "class=\"cp_field cp_field_" . $field->id . "\"";
		if ($field->field_type == 'select') { // SELECT FIELD TYPE

			echo $layout ? "<div $class>\n" : "<tr $class><td colspan=\"2\">\n";

			if ($select_label_pos == "above") {
				echo "<div class=\"cp_field_label" . $moduleclass_sfx . "\">" . htmlspecialchars($field->label) . "</div>\n";
			}
			echo "<select name=\"cp_" . htmlspecialchars($field->name) . "\" class=\"inputbox$moduleclass_sfx\" onchange=\"$auto_submit_function\">\n";


			if ($select_label_pos == "inside") {
				echo "<option value=\"\" class=\"empty$moduleclass_sfx\">" . htmlspecialchars($field->label) . "</option>\n";
			}
			else{
				echo "<option value=\"\" class=\"empty$moduleclass_sfx\"> - </option>\n";
			}

			foreach ($values as $value) {
				// using default only when no previous selection has been made
				if ($selected_field == "") {
					$selected = ($value->default ? "selected=\"selected\"" : "");
				} else {
					$selected = ($selected_field == $value->name ? "selected=\"selected\"" : "");
				}

				$class = trim($value->name) == "" ? "class=\"empty$moduleclass_sfx\"" : "";
				echo "<option value=\"" . htmlspecialchars($value->name) . "\" $class $selected>" . htmlspecialchars($value->label) . "</option>\n";
			}

			echo "</select>\n";

			echo $layout ? "</div>\n" : "</td></tr>\n";
		}
		elseif ($field->field_type == 'checkbox') { // CHECKBOX FIELD TYPE

			$class = "class=\"cp_field cp_field_" . $field->id . "\"";
			$name = 'cp_' . htmlspecialchars($field->name) . '[]';
			$field_label = htmlspecialchars($field->label);

			echo $layout ? "<fieldset $class>\n" : "";

			if ($show_checkbox_fieldname) {
				echo $layout ? "<legend>\n" : "<tr class=\"cp_field_label" . $moduleclass_sfx . "\"><td colspan=\"2\">\n";
				;
				echo $field_label;
				echo $layout ? "</legend>\n" : "</td></tr>\n";
			}

			foreach ($values as $value) {
				if (is_array($selected_field)) {
					$checked = (in_array($value->name, $selected_field) ? "checked=\"checked\"" : "");
				} else {
					$checked = "";
				}
				$label = htmlspecialchars($value->label);
				$class = "class=\"cp_cb_value cp_cb_value_" . $field->id .'_'.$value->id."\"";

				echo $layout ? "<div $class>\n" : "<tr $class><td>\n";

				if ($checkbox_label_pos == 'left') {
					if ($layout) {
						echo "<div class=\"cp_cb_label\">$label</div>\n";
						echo "<div class=\"cp_cb\"><input name=\"$name\" value=\"" . htmlspecialchars($value->name) . "\" type=\"checkbox\" $checked onclick=\"$auto_submit_function\"/></div>\n";
					} else {
						echo "<div class=\"cp_cb_label\">$label</div></td><td><input name=\"$name\" value=\"" . htmlspecialchars($value->name) . "\" type=\"checkbox\" $checked onclick=\"$auto_submit_function\"/>\n";
					}
				} else {
					if ($layout) {
						echo "<div class=\"cp_cb\"><input name=\"$name\" value=\"" . htmlspecialchars($value->name) . "\" type=\"checkbox\" $checked onclick=\"$auto_submit_function\"/></div>";
						echo "<div class=\"cp_cb_label\">$label</div>\n";
					} else {
						echo "<div class=\"cp_cb_label\">$label</div></td><td><input name=\"$name\" value=\"" . htmlspecialchars($value->name) . "\" type=\"checkbox\" $checked onclick=\"$auto_submit_function\"/>\n";
					}

				}
				echo $layout ? "</div>\n" : "</td></tr>\n";
			}

			echo $layout ? "</fieldset>\n" : "";
		}
		elseif ($field->field_type == 'text') {
			//TODO inside text label
			$name = 'cp_' . htmlspecialchars($field->name);
			$field_label = htmlspecialchars($field->label);
			$value = $selected_field != "" ? $selected_field : "";

			echo $layout ? "<div $class>\n" : "<tr $class><td colspan=\"2\">\n";
			if ($textfield_label_pos == 'before') {
				echo "<div class=\"cp_label\">$field_label</div>";
			}

			echo "<input type=\"text\" name=\"$name\" value=\"$value\" onclick=\"this.select()\" onchange=\"$auto_submit_function\"/>";

			if ($textfield_label_pos == 'after') {
				echo "<div class=\"cp_label\">$field_label</div>";
			}
			echo $layout ? "</div>\n" : "</td></tr>\n";
		}
	}
	if ($text_search) {

		$class = "class=\"cp_text_search\"";
		$javascript = "";
		if ($searched_text == "") {
			$javascript = "onblur=\"if(this.value=='') this.value='$text';\" onfocus=\"if(this.value=='$text') this.value='';\"";
		}
		echo $layout ? "<div $class>\n" : "<tr $class><td colspan=\"2\">\n";
		echo "<input type=\"text\" class=\"inputbox$moduleclass_sfx\" name=\"cp_text_search\" value=\"" . htmlspecialchars($text) . "\" $javascript onchange=\"$auto_submit_function\" />\n";
		echo $layout ? "</div>\n" : "</td></tr>\n";
	}

	if (!$auto_submit) {
		$class = "class=\"cp_submit\"";
		echo $layout ? "<div $class>\n" : "<tr $class><td colspan=\"2\">\n";
		echo "<input type=\"submit\" class=\"button $moduleclass_sfx\" name=\"submit_search\" value=\"" . JText::_('Search') . "\" />\n";
		echo $layout ? "</div>\n" : "</td></tr>\n";
	}

} else {
	echo $layout ? "<div $class>\n" : "<tr $class><td colspan=\"2\">\n";
	echo JText::_('CP_ERR_NO_FIELDS');
	echo $layout ? "</div>\n" : "</td></tr>\n";
}

echo $layout ? "</div>\n" : "</table>\n";
?>

</form>
</div>

