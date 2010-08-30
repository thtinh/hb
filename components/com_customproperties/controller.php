<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.4 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/


// no direct access

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Custom Properties Component Controller
 *
 * @package    Custom Properties
 * @subpackage Component
 */
class CustompropertiesController extends JController {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {
		parent::__construct();
		$this->registerTask('add', 		'assignCP');
		$this->registerTask('replace',	'assignCP');
	}

	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	function display() {

		$vName = JRequest::getCmd('view', 'show');
		$document = & JFactory::getDocument();
		$vType = $document->getType();

		switch ($vName) {
			case 'tagging' :

				$document->addStyleSheet('components/com_customproperties/css/customproperties.css');
				$vName = 'tagging';
				$vLayout = JRequest::getCmd('layout', 'default');
				// add dministrator views
				$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS);
				$view = & $this->getView($vName, $vType);
				//add administrators models
				$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS);
				$view->setModel($this->getModel('assign', 'CustompropertiesModel'));
				$view->setModel($this->getModel('cpfields', 'CustompropertiesModel'));

				break;
			case 'show' :
			default :
				$view 		=& $this->getView($vName, $vType);
				$vLayout 	= JRequest::getCmd('layout', 'default');
				$view->setModel($this->getModel('search', 'CustompropertiesModel'), true);
				break;
		}

		// Get/Create the view
		$view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . strtolower($vName) . DS . 'tmpl');

		// Set the layout
		$view->setLayout($vLayout);

		// Display the view
		$view->display();

	}

	/** Method to assign custom properties from the frontend
	 *
	 * @returns void
	 */
	function assignCP() {
		/*
		 * TODO This function is a verbatim copy of the function in backend's controller
		 * thus it is not DRY compliant :(
		 * sooner or later I'll fix it'
		 */

		global $cp_config;
		require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'cp_config.php');

		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS);
		$model = $this->getModel('assign', 'CustompropertiesModel');

		$content_id = JRequest::getVar('cid', 0, '', 'int');
		$database 	= JFactory::getDBO();
		$user 		= JFactory::getUser();

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
		if ($cp_config['frontend_tagging'] != 1) {
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