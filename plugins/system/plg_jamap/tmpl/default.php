<?php
/*
# ------------------------------------------------------------------------
# JA Map Plugin for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - GNU/GPL, http://www.gnu.org/copyleft/gpl.html
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# ------------------------------------------------------------------------
*/


// no direct access
defined('_JEXEC') or die('Restricted access'); 

$plgParams = array(
	'api_version' => '2',
	'context_menu' => 1,
	'to_location' => 'Ha Noi',
	'target_lat' => 0.000000,
	'target_lon' => 0.000000,
	'to_location_info' => '',
	'to_location_changeable' => 0,
	'from_location' => '',
	'map_width' => 500,
	'map_height' => 300,
	'maptype' => 'normal',
	'maptype_control_display' => 1,
	'maptype_control_style' => 'drop_down',
	'maptype_control_position' => 'top_right',
	'toolbar_control_display' => 1,
	'toolbar_control_style' => 'small_3d',
	'toolbar_control_position' => 'top_left',
	'display_layer' => 'none',
	'display_scale' => 1,
	'display_overview' => 1,
	'zoom' => 10,
	'api_key' => '',
	'sensor' => 0,
	'display_popup' => 0,
	'popup_width' => 640,
	'popup_height' => 480,
	'popup_type' => 'highslide'
);
$aUserSetting = $this->mapSetting;
//
$map = new stdClass ( );

$map->id = $this->mapId;
$aOptions = array();

foreach ($plgParams as $var => $value) {
	$map->$var = (isset ( $aUserSetting [$var] )) ? $aUserSetting [$var] : $this->plgParams->get( $var, $value );
	
	if(is_int($value)) {
		$map->$var = intval($map->$var);
	} elseif (is_float($value)) {
		$map->$var = floatval($map->$var);
	}
	
	if(is_int($map->$var) || is_float($map->$var)) {
		$aOptions[$var] = "'".$var."':".$map->$var."";
	} else {
		$str = $map->$var;
		$str = preg_replace('/(\n|\r\n|\'|\"|\/)/', '', $str);
		$aOptions[$var] = "'".$var."':'".$str."'";
	}
}
//exception: don't use default value of from_location
//because: google map can not calculate direction for every case
//$map->from_location = (isset ( $aUserSetting ['from_location'] )) ? $aUserSetting ['from_location'] : '';

//
$sOptions = implode(", ", $aOptions);
$map_id = 'ja-widget-map' . $map->id;

$popup_type = ($map->popup_type != 'global') ? 'modal="'.$map->popup_type.'"' : '';
?>

<div id="<?php echo $map_id; ?>-container" class="map-container" style="width:<?php echo $map->map_width; ?>px;">
	<?php if($map->display_popup): ?>
    {japopup type="inline" <?php echo $popup_type; ?> content="<?php echo $map_id; ?>-popup-container" width="<?php echo ($map->popup_width + 20); ?>" height="<?php echo ($map->popup_height + 60); ?>" onopen="JAMapOpenPopup<?php echo $map->id; ?>" onclose="JAMapClosePopup<?php echo $map->id; ?>"}
    <div class="map-open-link">
        <?php echo JText::_('OPEN IN NEW WINDOW'); ?>
    </div>
    {/japopup}
    <?php endif; ?>
    
    <div id="<?php echo $map_id; ?>" style="height:<?php echo $map->map_height; ?>px;">
    </div>
    
    <div id="<?php echo $map_id; ?>-route" class="map-route">
    </div>
</div>

<?php if($map->display_popup): ?>
<div id="<?php echo $map_id; ?>-popup-container" class="map-popup-container" style="width:<?php echo $map->popup_width; ?>px; height:<?php echo $map->popup_height; ?>px;">
    <div id="<?php echo $map_id; ?>-popup">
    </div>
    <div id="<?php echo $map_id; ?>-popup-route" class="map-popup-route">
    </div>
</div>
<?php endif; ?>
<script type="text/javascript">
//<![CDATA[
var sOptions = "{<?php echo $sOptions; ?>}";
var JAMapOptions<?php echo $map->id; ?> = Json.evaluate(sOptions);

var objWidgetMap<?php echo $map->id; ?> = new JAWidgetMap('<?php echo $map_id; ?>', JAMapOptions<?php echo $map->id; ?>);
var objWidgetMapPopup<?php echo $map->id; ?>;
window.addEvent('domready', function (){
	objWidgetMap<?php echo $map->id; ?>.displayMap('','',false);
});


function JAMapOpenPopup<?php echo $map->id; ?>()
{
	var popupOptions = Object.extend(JAMapOptions<?php echo $map->id; ?>, {size: new GSize(<?php echo $map->popup_width; ?>, <?php echo $map->popup_height; ?>), toolbar_control_style: 'large_3d', display_scale: 1} || {});
	objWidgetMapPopup<?php echo $map->id; ?> = new JAWidgetMap('<?php echo $map_id; ?>-popup', popupOptions);
	objWidgetMapPopup<?php echo $map->id; ?>.setCenter(objWidgetMap<?php echo $map->id; ?>);
}
function JAMapClosePopup<?php echo $map->id; ?>()
{
	objWidgetMap<?php echo $map->id; ?>.setCenter(objWidgetMapPopup<?php echo $map->id; ?>);
	return false;
}
//]]> 
</script>
