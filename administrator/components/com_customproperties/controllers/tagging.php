<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.2 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'contentelement.class.php');
/**
 * Custom Properties Component Controller - Tagging
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesControllerTagging extends JController {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('add', 		'assignCP');
		$this->registerTask('replace'	, 'assignCP');

	}

	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	function display() {

		$view = $this->getView('tagging', 'html');
		$view->setModel($this->getModel('assign', 'CustompropertiesModel'));
		$view->setModel($this->getModel('cpfields', 'CustompropertiesModel'));

		$view->display();
	}

	/** Method to assign custom properties from the frontend
	 *
	 * @returns void
	 */
	function assignCP() {

		global $cp_config;
		require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'cp_config.php');

		$model = $this->getModel('assign', 'CustompropertiesModel');

		$content_id = JRequest::getVar('cid', 0, '', 'int');
		$database = JFactory::getDBO();
		$user = JFactory::getUser();

		switch ($this->getTask()) {
			case 'add' :
				$action = 'add';
				break;
			case 'replace' :
				$action = 'replace';
				break;
			default :
				return;
		}

		$option 	= JRequest::getCmd('option'		, 'com_customproperties');
		$controller = JRequest::getCmd('controller'	, 'tagging');
		$view 		= JRequest::getCmd('view'		, 'tagging');

		$return_to = "index.php?option=$option&controller=$controller&view=$view&tmpl=component&cid=$content_id";

		if ($content_id === 0) {
			$this->setRedirect($return_to, JText::_('CP_ERR_INVALID_ID'), 'error');
			return;
		};
		if ($cp_config['frontend_tagging'] == -1) {
			$this->setRedirect($return_to, JText::_('CP_ERR_FUNCTION_DISABLED'), 'error');
			return;
		}
		if ($user->get('gid') < $cp_config['editing_level']) {
			$this->setRedirect($return_to, JText::_('CP_ERR_NOAUTH'), 'error');
			return;
		}

		$model->assignCustomProperties($action);
		$this->setRedirect($return_to);

	}

}