<?php
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_SITE.DS.'libraries'.DS.'vxghelper'.DS.'helper.php');
$limit = $params->get( 'limit', 5 );
$catid = $params->get( 'catid', "5" );
$trimtext = $params->get('trimtext',0);
$contentHelper = new helper();
$rows = $contentHelper->getContentbyCatid($catid,$limit,false);
require(JModuleHelper::getLayoutPath('mod_hb_news'));