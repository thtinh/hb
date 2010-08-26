<?php
/**
 * SlideShow Module
 * 
 * @package    Joomla
 * @subpackage Modules
 * @link http://www.joomvision.com
 * @license        GNU/GPL
 * mod_slideshow is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).DS.'helper.php');

$slides = modJVSlideshowHelper::getList($params);

if($params->get('autorun')==0)
	$autorun = "false";
else
	$autorun = "true";

require(JModuleHelper::getLayoutPath('mod_jvslideshow'));
?>