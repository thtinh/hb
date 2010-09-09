<?php
$ua = browser_info();
$menus = &JSite::getMenu();
$menu  = $menus->getActive();
$itemid  = $menu->id;
if ($itemid=='') $itemid = '134';
$fieldid = 1;
$database = JFactory::getDBO();
//$query = "SELECT DISTINCT name as value, label as text FROM #__custom_properties_values as v WHERE field_id = $fieldid";
$query = "select distinct illness as text,illness as value from #__kid";
$css_class = ($ua['firefox'] || $ua['safari']) ? "lightcurve lightshadow" : "";
$illness_html[] = JHTML::_('select.option',  '', ' -' );
$database->setQuery( $query );
$illnesslist = array_merge( $illness_html, $database->loadObjectList() );
$selectedIllness = JRequest::getVar('illness',"");
$javascript = 'onchange="document.searchForm.cp_illness.value = this.options[this.options.selectedIndex].value;document.searchForm.submit();"';
$lists['illness']= JHTML::_('select.genericlist',$illnesslist,'illness', 'class="inputbox '. $css_class.'" size="1" '. $javascript, 'value', 'text', $selectedIllness);

$fieldid = 2;
//$query = "SELECT DISTINCT name as value, label as text FROM #__custom_properties_values as v WHERE field_id = $fieldid";
$query = "select distinct year(dob) as value, year(dob) as text from #__kid";
$database->setQuery( $query );
$year_html[] = JHTML::_('select.option',  '', '-' );
$selectedYear = JRequest::getVar('year',"");
$yearlist = array_merge( $year_html, $database->loadObjectList() );
$javascript = 'onchange="document.searchForm.cp_year.value = this.options[this.options.selectedIndex].value;document.searchForm.submit();"';
$lists['year']= JHTML::_('select.genericlist',$yearlist,'year', 'class="inputbox '. $css_class.'" size="1" '. $javascript, 'value', 'text', $selectedYear);
$lists['year'] =  html_entity_decode($lists['year']);

require(JModuleHelper::getLayoutPath('mod_application_finder'));

function browser_info($agent=null) {
    // Declare known browsers to look for
    $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape',
            'konqueror', 'gecko');

    // Clean up agent and build regex that matches phrases for known browsers
    // (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
    // version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
    $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

    // Find all phrases (or return empty array if none found)
    if (!preg_match_all($pattern, $agent, $matches)) return array();

    // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
    // Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
    // in the UA).  That's usually the most correct.
    $i = count($matches['browser'])-1;
    return array($matches['browser'][$i] => $matches['version'][$i]);
}
?>
