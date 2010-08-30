<?php
/**
 * @version		$Id: cptags.php,v 1.2 2009-03-04 18:18:03 forghy Exp $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Editor Image buton
 *
 * @package Editors-xtd
 * @since 1.5
 */
class plgButtonCptags extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgButtonCptags(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert ) <<== ?? hardly true
	 */
	function onDisplay($name)
	{

		global $mainframe, $cp_config;
		include_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_customproperties'.DS.'cp_config.php');

		$user 	= JFactory::getUser();
		$aid 	= $user->get('aid',0);

		$button = new JObject();
		$button->set('name', false);

		//show if cp_config is included
		if(	$mainframe->isSite() && ($cp_config['frontend_tagging'] != '1') ){
			return $button;
		}
		// show only to users with proper rights
		if($aid < $cp_config['editing_level']){
			return $button;
		}

		//show only on com_content
		$option = JRequest::getCmd('option','') ;
		if($option != 'com_content'){
			return $button;
		}

		if($content_id = JRequest::getVar('cid', null, '', 'array')){
			$content_id = $content_id[0];
		}
		elseif($content_id = JRequest::getVar('id', null)){}
		else{
			//false button
			return $button;
		}

		$doc = & JFactory::getDocument();
		$doc->addStyleDeclaration(".button2-left .cp_tags_btn{	background: url(".JURI::root()."administrator/components/com_customproperties/images/j_button2_cptags.png) 100% 0 no-repeat; }");


		$link = 'index.php?option=com_customproperties&amp;controller=tagging&amp;view=tagging&amp;tmpl=component&id='.$content_id;

		JHTML::_('behavior.modal');

		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('CP Tags'));
		$button->set('name', 'cp_tags_btn');
		$button->set('img', 'cp_tags_btn.gif');
		$button->set('options', "{handler: 'iframe', size: {x: 570, y: 400}}");
		return $button;
	}
}