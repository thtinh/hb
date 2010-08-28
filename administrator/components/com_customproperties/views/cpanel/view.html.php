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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Config & About  View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewCpanel extends JView {
	/**
	 * CP Fields assign method
	 * @return void
	 **/
	function display($tpl = null) {
		$model = & JModel :: getInstance('config', 'custompropertiesModel');
		$this->assignRef('is_writable', $model->isWritable());
		$this->assignRef('parameters', $model->getParameters());

		parent :: display($tpl);
	}

}