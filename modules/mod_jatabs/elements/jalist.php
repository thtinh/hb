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

class JElementJalist extends JElement
{
	/**
	* Element type
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Jalist';

	function fetchElement($name, $value, &$node, $control_name)
	{	
		$options = array ();
		foreach ($node->children() as $option)
		{
			$val	= $option->attributes('value');
			$text	= $option->data();
			$options[] = JHTML::_('select.option', $val, JText::_($text));
		}
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );
		$class .= " onchange=\"javascript: switchGroup(this)\"";
		$str = JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $class, 'value', 'text', $value, $control_name.$name);		
		$str .= "<script type=\"text/javascript\" language=\"javascript\">				
					function getElementsByGroup (group) {
					  if (!group) return;
					  var els=[];					 
					  $$(document.adminForm.elements).each(function(el){ 
						if (el.id.test(group+'-')) els.push(el);
					  });
						
					  return els;
					}
					
					function switchGroup (selectcontrol) {
						selectedel = selectcontrol.options[selectcontrol.selectedIndex];
						groupsel = selectedel.value.split('-')[0];
					  $$(selectcontrol.options).each(function (el) {
					  	var group = el.value.split('-')[0];
  						var groups = getElementsByGroup (group);
  						if (!groups) return;
						var disabled = (groupsel==group)?'':'disabled';
  						groups.each(function(g){
  							g.disabled = disabled;
  						});
					  });
					}
					
					function getTR (el) {
					  el = $(el);
					  var p;
					  while ((p = el.getParent()) && p.tagName != 'TR') {el = p;}
					  
					  return p;
					}
					
					function disableall(){
					  var selectct = $('params".$name."');
					  switchGroup(selectct);					 
					}

					function jaInit () {
					   disableall();
					   document.adminForm.onsubmit = enableall;
					}

					function enableall(){
					  var selectct = $('params".$name."');
					  $$(selectct.options).each(function (el) {
					  	var group = el.value.split('-')[0];
  						var groups = getElementsByGroup (group);
  						if (!groups) return;
  						groups.each(function(g){
  							g.disabled = '';
  						});
					  });
					}

					window.addEvent('load', jaInit);
				</script>";
		return $str;
	}
}
?>
