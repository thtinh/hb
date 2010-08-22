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

// no direct access
defined('_JEXEC') or die('Restricted access');

class modJaTabs extends JObject
{
	var $_modulename        =	"";
	var $_ids  				=	"";
	var $_catid 			=	"";
	var $_view 				=	"";	
	/** @var int */
	var $_Height 			= 	'auto';
	/** @var int */
	var $_Width 			= 	'100%';
	/** @var string */
	var $_position 			= 	"top";
	/** @var int */
	var $_tHeight 			= 	30;
	/** @var int */
	var $_tWidth 			= 	120;
	/** @var string */
	var $_type 				= 	"module";
	/** @var string */
	var $_mposition 	= 	"left";
	/** @var string */
	var $_content 			= 	"";
	/** @var string */
	var $_result 			= 	"";
	/** @var string */
	var $_animType 			= 	"animMoveLeft";  //animMoveLeft, animFade, animMoveDown
	/** @var string */
	var $_mouseType 		= 	"click"; //click, mouseove
	/** @var string */
	var $_style 			= 	""; //click, mouseove
	/** @var int */
	var $_duration 			= 	100;
	
    var $_ajax 			    = 	'false';

	function __construct( $params ){
		
		$this->_style = $params->get('style',$this->_style);

		$this->_Height = $params->get('Height',$this->_Height);
		
		$this->_Width = $params->get('Width',$this->_Width);
		
		$this->_position = $params->get('position',$this->_position);
		
		$this->_tHeight = $params->get('tHeight',$this->_tHeight);
		
		$this->_tWidth = $params->get('tWidth',$this->_tWidth);
		
		$this->_type = $params->get('type',$this->_type);
		
		$this->_view = $params->get('view', $this->_view);
		
		$this->_mposition = $params->get('modules-position',$this->_mposition );

		$this->_content = $params->get('content-content',$this->_content);
		
		$this->_animType = $params->get('animType',$this->_animType);
		
		$this->_mouseType = $params->get('mouseType',$this->_mouseType);

		$this->_duration = $params->get('duration',$this->_mouseType);
		
		$this->_ajax = $params->get('ajax',$this->_ajax);		
		
		$this->_modulename = $params->get('module-modulename',$this->_modulename);
		
		$this->_ids	=  $params->get('articlesIDs-ids',$this->_ids);
		
		$this->_catid = $params->get('categoryID-catid',$this->_catid);
		$this->_colors = $params->get('colors',$this->_catid);
	}

	function getString()
	{
		
		if($this->_content || $this->_mposition || $this->_ids || $this->_catid || $this->_modulename){
		
			$this->begintabs();
			
			switch($this->_type){
			
				case "modules": $this->getPosition();
								break;
				case "module": $this->getModulename();
								break;
				case "articlesIDs": $this->getIds();
								break;
				case "categoryID": $this->getCatid();
								break;								
				case "content": 
				default:
								$this->getContent();
								break;			
			}
			
			$this->endtabs();

			$row = new stdClass();
			
			$row->text = $this->_result;

	    jimport('joomla.plugin.helper');
	    JPluginHelper::importPlugin('content', 'ja_tabs');
			
			if (class_exists('plgContentJA_tabs')) {
				$sub = null;
				$plg = new plgContentJA_tabs($sub);
				$pparams=array();
				$plg->onPrepareContent($row, $pparams, 0);
			}	
			
			return $row->text;
		}
		else return "";
	}
	
	function begintabs(){
			$this->_result = "{jatabs";
			if($this->_type!="articlesIDs" && $this->_type!="categoryID")  	
			$this->_result .= " type=\"".$this->_type."\"";
			
			$this->_result .= " animType=\"".$this->_animType."\"";
			$this->_result .= " style=\"".$this->_style."\"";
			$this->_result .= " position=\"".$this->_position."\"";
			$this->_result .= " widthTabs=\"".$this->_tWidth."\"";
			$this->_result .= " heightTabs=\"".$this->_tHeight."\"";
			$this->_result .= " width=\"".$this->_Width."\"";
			$this->_result .= " height=\"".$this->_Height."\"";
			$this->_result .= " mouseType=\"".$this->_mouseType."\"";
			$this->_result .= " duration=\"".$this->_duration."\"";
			$this->_result .= " colors=\"".$this->_colors."\"";
			if ($this->_ajax=='true') {
				$this->_result .= " ajax=\"".$this->_ajax."\"";
			}			
	}
	
	function endtabs(){
		$this->_result .= "{/jatabs}";
	}
	
	function getPosition(){
		if($this->_mposition){
			$this->_result .= " module=\"".$this->_mposition."\"";
			$this->_result .= " }";
		}
	}
	function getModulename()
	{
		if($this->_modulename)
		{
			
			$this->_result .= " modulename=\"".$this->_modulename."\"";
			$this->_result .= " }";
		}
	}
	
	function getIds()
	{
		if($this->_ids)
		{	
			$this->_result .= " view=\"".$this->_view."\"";
			$this->_result .= " type=\"articles\"";			
			$this->_result .= " ids=\"".$this->_ids."\"";
			$this->_result .= " }";
		}
	}
	
	
	function getCatid()
	{
		if($this->_catid )
		{	
				
			$this->_result .= " view=\"".$this->_view."\"";
			$this->_result .= " type=\"articles\"";
			$this->_result .= " catid=\"".$this->_catid."\"";
			$this->_result .= " }";
		}
	}
	
	function getContent(){
		$this->_result .= " }";
		if($this->_content){
			$this->_result .= $this->_content;
		}
	}
}
?>
