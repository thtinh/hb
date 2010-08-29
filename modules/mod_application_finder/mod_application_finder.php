<?php

$menus = &JSite::getMenu();
$menu  = $menus->getActive();
$itemid  = $menu->id;
if ($itemid=='') $itemid = '134';
$fieldid = 1;
$database = JFactory::getDBO();
$query = "SELECT DISTINCT name as value, label as text FROM #__custom_properties_values as v WHERE field_id = $fieldid";

$illness_html[] = JHTML::_('select.option',  '', ' -' );
$database->setQuery( $query );
$illnesslist = array_merge( $illness_html, $database->loadObjectList() );
$selectedIllness = "";
$javascript = 'onchange="document.searchForm.cp_application.value = this.options[this.options.selectedIndex].value;"';
$lists['illness']= JHTML::_('select.genericlist',$illnesslist,'illness', 'class="inputbox lightcurve lightshadow" size="1" '. $javascript, 'value', 'text', $selectedIllness);

$fieldid = 2;
$query = "SELECT DISTINCT name as value, label as text FROM #__custom_properties_values as v WHERE field_id = $fieldid";
$database->setQuery( $query );
$year_html[] = JHTML::_('select.option',  '', '-' );
$selectedYear = "";
$yearlist = array_merge( $year_html, $database->loadObjectList() );
$javascript = 'onchange="document.searchForm.cp_product.value = this.options[this.options.selectedIndex].value;"';
$lists['year']= JHTML::_('select.genericlist',$yearlist,'year', 'class="inputbox lightcurve lightshadow" size="1" '. $javascript, 'value', 'text', $selectedYear);
$lists['year'] =  html_entity_decode($lists['year']);
require(JModuleHelper::getLayoutPath('mod_application_finder'));

?>