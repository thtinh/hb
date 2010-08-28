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
 * Custom Properties Component Controller - About and config
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesControllerCpanel extends JController {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {
		parent :: __construct();

	}

	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	function display() {
		switch ($this->getTask()) {
			case 'configure' :
				JRequest :: setVar('layout', 'default');
				JRequest :: setVar('view', 'cpanel');
				break;
			case 'about' :
			default :
				JRequest :: setVar('layout', 'about');
				JRequest :: setVar('view', 'cpanel');
				break;
		}
		parent :: display();
	}

	function saveConfig() {
		$model = & $this->getModel('config');
		$link = "index.php?option=com_customproperties&controller=cpanel&task=configure";
		if ($model->saveConfig()) {
			$msg = JText :: _('Configuration saved');
			$msg_type = 'message';
		} else {
			$msg = implode(',', $this->getErrors());
			$msg = JText :: _('ERRCANTSAVECFG') . $msg;
			$msg_type = 'error';
		}
		$this->setRedirect($link, $msg, $msg_type);
	}

}