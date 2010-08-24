<?php
/**
* @version		$Id: mod_banners.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
 * 
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_SITE.DS.'components'.DS.'com_vxgajax'.DS.'controller.php');
$helper = new AjaxController();
$catid = $params->get("catid",0);
$result = $helper->getContentCategory($catid);
$pagination = $result["pagination"];
$sponsorslist = $result["content"];

$doc =& JFactory::getDocument();
require(JModuleHelper::getLayoutPath('mod_sponsors_list'));
