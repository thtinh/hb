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
 

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

class JElementJamap extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Jamap';

	function fetchElement( $name, $value, &$node, $control_name ) {
		if (substr($name, 0, 1) == '@'  ) {
			$name = substr($name, 1);
			if (method_exists ($this, $name)) {
				return $this->$name ($name, $value, $node, $control_name);
			}
		} else {
			$subtype = ( isset( $node->_attributes['subtype'] ) ) ? trim($node->_attributes['subtype']) : '';
			if (method_exists ($this, $subtype)) {
				return $this->$subtype ($name, $value, $node, $control_name);
			}
		}
		return; 
	}
	
	function fetchTooltip( $label, $description, &$node, $control_name, $name )
	{
		if (substr($name, 0, 1) == '@' || !isset( $node->_attributes['label'] ) || !$node->_attributes['label']) return;
		else return parent::fetchTooltip ($label, $description, $node, $control_name, $name);
	}

	/**
	 * Subtype - map_key, subtype="map_key"
	 */
	function mapkey( $name, $value, &$node, $control_name ) {
		$paramname = ''.$control_name.'['.$name.']';
		$id = $control_name.$name;
		$cols = ( isset( $node->_attributes['cols'] ) && $node->_attributes['cols'] != '')  ? 'cols="'.intval($node->_attributes['cols']).'"' : '';
		$rows = ( isset( $node->_attributes['rows'] ) && $node->_attributes['rows'] != '')  ? 'rows="'.intval($node->_attributes['rows']).'"' : '';
		//LOAD ASSETS
		$plugin = new stdClass();
		$plugin->type = 'system';
		$plugin->name = 'plg_jamap';
		
		//popup
		JHTML::script('modal.js','media/system/js/');
		JHTML::stylesheet('modal.css','media/system/css/');
		//
		JHTML::stylesheet('style.css','plugins/'.$plugin->type.'/'.$plugin->name.'/');
		JHTML::script('script.js','plugins/'.$plugin->type.'/'.$plugin->name.'/');
		JHTML::script('jagencode.js','plugins/'.$plugin->type.'/'.$plugin->name.'/assets/');
		//google map 
		$map_js = 'http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=' . $value;
		JHTML::script('',$map_js);
		//context menu
		JHTML::script('contextmenucontrol.js','plugins/'.$plugin->type.'/'.$plugin->name.'/assets/');
		//
		$html = "";
		$html .= "\n\t<textarea name=\"$paramname\" id=\"$id\" $cols $rows >$value</textarea><br />";
		return $html;
	}	
	
	/**
	 * Subtype - map_code, subtype="map_code"
	 */
	function mapcode ( $name, $value, &$node, $control_name ){
		$paramname = ''.$control_name.'['.$name.']';
		$id = $control_name.$name;
		$cols = ( isset( $node->_attributes['cols'] ) && $node->_attributes['cols'] != '')  ? 'cols="'.intval($node->_attributes['cols']).'"' : '';
		$rows = ( isset( $node->_attributes['rows'] ) && $node->_attributes['rows'] != '')  ? 'rows="'.intval($node->_attributes['rows']).'"' : '';
		
		$html = "";
		$html .= "\n\t<a name=\"mapPreview\"></a>";
		$html .= "\n\t<textarea name=\"$paramname\" id=\"$id\" $cols $rows >$value</textarea><br />";
		$html .= "\n\t".'<a href="javascript: CopyToClipboard(\''.$id.'\');">'.JText::_('SELECT ALL').'</a>';
		$html .= "\n\t".'&nbsp;|&nbsp;';
		$html .= "\n\t".'<a id="jaMapPreview" href="#mapPreview" >'.JText::_('PREVIEW MAP').'</a>';		
		$html .= '<div id="map-preview-container"></div>';
		return $html;
	
	}
} 