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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );

class plgSystemPlg_JAMap extends JPlugin {
	var $_plgCode = "#{jamap(.*?)}#i";
	var $_plgCodeModifier = "#{jamap(.*?)}#e";
	var $_plgCodeHolder = "{JA-MAP-HOLDER-%d}";
	var $_plgCodeMakeHolder = "'{JA-MAP-HOLDER-' . plgSystemPlg_JAMap::getCount() . '}'";
	var $_aUSetting = array ();
	
	var $mapSetting = array ();
	var $mapId = null;
	
	function plgSystemPlg_JAMap(&$subject, $config) {
		global $mainframe;
		parent::__construct ( $subject, $config );
		
		$this->plugin = &JPluginHelper::getPlugin ( 'system', 'plg_jamap' );
		$this->plgParams = new JParameter ( $this->plugin->params );
	}
	
	function onAfterRender() {
		global $mainframe;
		global $option;
		
		if ($mainframe->isAdmin ()) {
			return;
		}
		$this->getCount ( true );
		$body = JResponse::getBody ();
		
		$plgParams = $this->plgParams;
		$disable_map = $plgParams->get ( 'disable_map', 0 );
		if ($disable_map) {
			$body = $this->removeCode ( $body );
			JResponse::setBody ( $body );
			return;
		}
		
		if (! preg_match ( $this->_plgCode, $body )) {
			return;
		}
		
		$this->_aUSetting = $this->getUserSetting ( $body );
		
		if (count ( $this->_aUSetting ) > 0) {
			$body = $this->stylesheet ( $this->plugin, $body );
			
			foreach ( $this->_aUSetting as $id => $sSetting ) {
				$this->mapId = $id;
				$this->mapSetting = $this->parseParams ( $sSetting );
				
				$output = $this->loadLayout ( $this->plugin, 'default' );
				
				$holder = sprintf ( $this->_plgCodeHolder, $id );
				$body = str_replace ( $holder, $output, $body );
			}
		}
		
		JResponse::setBody ( $body );
	}
	function getCount($reset = false) {
		static $count = 0;
		if ($reset)
			$count = - 1;
		return $count ++;
	}
	function getUserSetting(&$text) {
		if (preg_match_all ( $this->_plgCode, $text, $matches )) {
			$text = preg_replace ( $this->_plgCodeModifier, $this->_plgCodeMakeHolder, $text, - 1, $count );
			
			return $matches [1];
		} else {
			return array ();
		}
	
	}
	function parseParams($string) {
		$string = html_entity_decode ( $string, ENT_QUOTES );
		$regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
		$params = null;
		if (preg_match_all ( $regex, $string, $matches )) {
			for($i = 0; $i < count ( $matches [1] ); $i ++) {
				$key = $matches [1] [$i];
				$value = $matches [3] [$i] ? $matches [3] [$i] : ($matches [4] [$i] ? $matches [4] [$i] : $matches [5] [$i]);
				$params [$key] = $value;
			}
		}
		return $params;
	}
	function removeCode($content) {
		return preg_replace ( $this->_plgCode, '', $content );
	}
	
	function getLayoutPath($plugin, $layout = 'default') {
		global $mainframe;
		
		// Build the template and base path for the layout
		$tPath = JPATH_BASE . DS . 'templates' . DS . $mainframe->getTemplate () . DS . 'html' . DS . $plugin->name . DS . $layout . '.php';
		$bPath = JPATH_BASE . DS . 'plugins' . DS . $plugin->type . DS . $plugin->name . DS . 'tmpl' . DS . $layout . '.php';
		// If the template has a layout override use it
		if (file_exists ( $tPath )) {
			return $tPath;
		} elseif (file_exists ( $bPath )) {
			return $bPath;
		}
		return '';
	}
	
	function loadLayout($plugin, $layout = 'default') {
		$layout_path = $this->getLayoutPath ( $plugin, $layout );
		if ($layout_path) {
			ob_start ();
			require $layout_path;
			$content = ob_get_contents ();
			ob_end_clean ();
			return $content;
		}
		return '';
	}
	
	function stylesheet($plugin, $bodyString) {
		global $mainframe;
		$params = new JParameter ( $this->plugin->params );
		
		$assets_url = JURI::base () . 'plugins/' . $plugin->type . '/' . $plugin->name . '/';
		$headtag = array ();
		$headtag [] = '<link href="' . $assets_url . 'style.css" type="text/css" rel="stylesheet" />';
		$headtag [] = '<script src="' . $assets_url . 'script.js" type="text/javascript" ></script>';
		
		//google map 
		$api_version = $params->get ( 'api_version', '2' );
		$api_key = $params->get ( 'api_key', '' );
		$sensor = ($params->get ( 'sensor', 1 ) == 1) ? 'true' : 'false';
		
		$map_js = 'http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=' . $sensor . '&amp;key=' . $api_key;
		$headtag [] = '<script src="' . $map_js . '" type="text/javascript" ></script>';
		//context menu
		$headtag [] = '<script src="' . $assets_url . 'assets/contextmenucontrol.js" type="text/javascript" ></script>';
		
		$bodyString = str_replace ( '</head>', "\t" . implode ( "\n", $headtag ) . "\n</head>", $bodyString );
		return $bodyString;
	}
}
?>