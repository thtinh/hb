<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.3 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');
/**
 * Frontend tagging View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewTagging extends JView {
	/**
	 * CP Fields assign method
	 * @return void
	 **/
	function display($tpl = null) {

		global $mainframe,$cp_config;
		require_once(JPATH_COMPONENT_ADMINISTRATOR .DS.'cp_config.php');

		// content element
		$ce_name		 		= JRequest::getVar('ce_name', null, '', 'string');
		if(! $content_element 	= getContentElementByName("$ce_name")) {
			$content_element 	= getContentElementByName("content");
			$ce_name			= 'content';
		}

		// CP fields
		$cp 		= $this->getModel('cpfields');
		$cpfields	= $cp->getList(false);

		$assign 	= $this->getModel('assign');
		$content_id = $assign->_id;
		$item_title = $assign->getTitle();
		$properties = $assign->getProperties();

		$user = & JFactory::getUser();
		$aid = $user->get('aid',0);

		if(	$mainframe->isSite() && ($cp_config['frontend_tagging'] != '1') ){
			JError::raiseError( 500, JText::_( 'CP_ERR_FUNCTION_DISABLED' ) );
		}
		if($aid < $cp_config['editing_level']) {
			JError::raiseError( 500, JText::_( 'CP_ERR_NOAUTH' ) );
		}
		if(empty($content_id) ){
			JError::raiseError( 500, JText::_( 'CP_ERR_INVALID_ID' ) );
		}
		$this->assignRef('content_element', $content_element);
		$this->assignRef('ce_name',			$ce_name);
		$this->assignRef('cpfields',		$cpfields);
		$this->assignRef('content_id',		$content_id);
		$this->assignRef('item_title',		$item_title);
		$this->assignRef('properties',		$properties);

		parent::display($tpl);
	}

}
