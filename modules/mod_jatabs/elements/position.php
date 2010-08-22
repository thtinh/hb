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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementPosition extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Position'; 

	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = $this->getPositions();

		$arrOpt = array();
		for($i=0; $i < count($options); $i++){
			$arrOpt[$i]['keys'] = $arrOpt[$i]['value'] = $options[$i]->position;
		}
		array_unshift($arrOpt, JHTML::_('select.option', '0', '- '.JText::_('Select position').' -', 'keys', 'value'));
		return JHTML::_('select.genericlist',  $arrOpt, ''.$control_name.'['.$name.']', 'class="inputbox"', 'keys', 'value', $value, $control_name.$name );
	}
	
	function getPositions()
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT DISTINCT position'
		. ' FROM #__modules AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.position'
		;
		$db->setQuery( $query );
		$db->getQuery();
		$options = $db->loadObjectList();

		return $options;
	}
	
}