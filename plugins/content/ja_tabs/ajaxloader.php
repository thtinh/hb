<?php
/*
# ------------------------------------------------------------------------
# JA Rasite - Stable - Version 1.0 - Licence Owner JA115884
# ------------------------------------------------------------------------
# Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
# ------------------------------------------------------------------------
*/

if (!defined ('_JEXEC')) {
	define( '_JEXEC', 1 );
	$path = dirname(dirname(dirname(dirname(__FILE__))));
	define('JPATH_BASE', $path );

	if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
		//Apache CGI
		$_SERVER['PHP_SELF'] =  rtrim(dirname(dirname(dirname($_SERVER['PHP_SELF']))), '/\\');
	} else {
		//Others
		$_SERVER['SCRIPT_NAME'] =  rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))), '/\\');
	}
	
	define( 'DS', DIRECTORY_SEPARATOR );
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;

	$mainframe =& JFactory::getApplication('site');
	
	// set the language
	$mainframe->initialise();
	// SSL check - $http_host returns <live site url>:<port number if it is 443>
	JPluginHelper::importPlugin('system', null, false);
	// trigger the onStart events
	$mainframe->triggerEvent( 'onStart' );
}

$tab = '';
if(isset($_REQUEST['tab'])){
	$tab = $_REQUEST['tab'];
}

switch ($_REQUEST ['type']) {
  case 'content':
	writeContentArticle($tab);
    break;
  case 'modules':
  	writeContentModules($tab);
    break;
}

function writeContentArticle($tab){
	$row = array();
	$row = getList($tab);
	if(isset($_REQUEST['view']) && $_REQUEST['view']=='fulltext'){
	  	$row->text = $row->introtext.$row->fulltext;
	}else{
		$row->text = $row->introtext;
	}
	  print_r($row->text);exit;
	jimport('joomla.plugin.helper');
	JPluginHelper::importPlugin('content');
	
	$app = &JFactory::getApplication();
	$pparams = new JParameter('');
	$app->triggerEvent('onPrepareContent', array($row, $pparams, 0));
	echo $row->text;
}

function writeContentModules($tab){	
	jimport('joomla.application.module.helper');
	$modules = _load($tab);	
	echo JModuleHelper::renderModule($modules[0]);	
}


/**
 * Load published modules
 *
 * @access	private
 * @return	array
 */
function _load($module){
	global $mainframe;

	static $modules;
	if (isset($modules)) {
		return $modules;
	}

	$user	=& JFactory::getUser();
	$db		=& JFactory::getDBO();

	$aid	= $user->get('aid', 0);

	$modules	= array();

	$query = 'SELECT id, title, module, position, content, showtitle, control, params'
		. ' FROM #__modules AS m'
		. ' LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id'
		. " WHERE m.module='$module'";

	$db->setQuery( $query );

	if (null === ($modules = $db->loadObjectList())) {
		JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'Error Loading Modules' ) . $db->getErrorMsg());
		return false;
	}



		//determine if this is a custom module
		$file					= $modules[0]->module;
		$custom 				= substr( $file, 0, 4 ) == 'mod_' ?  0 : 1;
		$modules[0]->user  	= $custom;
		// CHECK: custom module name is given by the title field, otherwise it's just 'om' ??
		$modules[0]->name		= $custom ? $modules[0]->title : substr( $file, 4 );
		$modules[0]->style		= null;
		$modules[0]->position	= strtolower($modules[0]->position);

	return $modules;
}

function getList($ids='', $catid=''){
	global $mainframe;
	$db 	=& JFactory::getDBO();
	$user 	=& JFactory::getUser();
	$aid	= $user->get('aid', 0);

	$contentConfig	= &JComponentHelper::getParams( 'com_content' );
	$noauth			= !$contentConfig->get('shownoauth');

	jimport('joomla.utilities.date');
	$date = new JDate();
	$now = $date->toMySQL();

	$nullDate = $db->getNullDate();

	// query to determine article count
	$query = 'SELECT a.* ' .		
		' FROM #__content AS a' .
		' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
		' INNER JOIN #__sections AS s ON s.id = a.sectionid';
	$query .=	" WHERE a.id = $ids";
		
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows[0];
}
?>