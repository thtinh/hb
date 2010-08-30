<?php

$menus = &JSite::getMenu();
$menu  = $menus->getActive();
$itemid  = $menu->id;
if ($itemid=='') $itemid = '134';
$fieldid = 1;
$database = JFactory::getDBO();
$query = "SELECT DISTINCT name as value, label as text FROM #__custom_properties_values as v WHERE field_id = $fieldid";

$application_html[] = JHTML::_('select.option',  '', '- '. JText::_( 'Chọn ứng dụng' ) .' -' );
$database->setQuery( $query );
$applicationlist = array_merge( $application_html, $database->loadObjectList() );
$selectedApplication = "";
$javascript = 'onchange="document.searchForm.cp_application.value = this.options[this.options.selectedIndex].value;"';
$lists['application']= JHTML::_('select.genericlist',$applicationlist,'application', 'class="inputbox" size="1" '. $javascript, 'value', 'text', $selectedApplication);

$fieldid = 2;
$query = "SELECT DISTINCT name as value, label as text FROM #__custom_properties_values as v WHERE field_id = $fieldid";
$database->setQuery( $query );
$product_html[] = JHTML::_('select.option',  '', '- '. JText::_( 'Chọn sản phẩm' ) .' -' );
$selectedProduct = "";
$productlist = array_merge( $product_html, $database->loadObjectList() );
$javascript = 'onchange="document.searchForm.cp_product.value = this.options[this.options.selectedIndex].value;"';
$lists['product']= JHTML::_('select.genericlist',$productlist,'product', 'class="inputbox" size="1" '. $javascript, 'value', 'text', $selectedProduct);
$lists['product'] =  html_entity_decode($lists['product']);
require(JModuleHelper::getLayoutPath('mod_application_finder'));

?>