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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Utilities View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewUtilities extends JView {
	/**
	 * CP Utilities
	 * @return void
	 **/
	function display($tpl = null) {

		$task		= JRequest::getCmd('task');

		switch($task){
			case 'checkdirs':
				$this->assignRef('dirs', getDirs() );
				break;
			case 'showce':
				$this->assignRef('available_content_elements',	getAvailableContentElements() );
				$this->assignRef('installed_content_elements',	getInstalledContentElements(true) );
				break;
			default :
				break;
		}

		parent :: display($tpl);
	}

}

/** get directories to check are writable
 * @return array associative array ith directories to be checked
 * */
function getDirs() {

	$dirs = array (
		'component_admin' => array (
			'base' => JPATH_COMPONENT_ADMINISTRATOR,
			'content elements' => JPATH_COMPONENT_ADMINISTRATOR.DS."contentelements"
		),
		'component_site' => array (
			'base' => JPATH_COMPONENT_SITE ,
			'thumbnails' => JPATH_COMPONENT_SITE.DS."images"
		),
		'mod_cpsearch' => array (
			'base' => JPATH_SITE.DS."modules".DS."mod_cpsearch".DS."mod_cpsearch.php"
		),
		'mod_cpcloud' => array (
			'base' => JPATH_SITE.DS."modules".DS."mod_cpcloud".DS."mod_cpcloud.php"
		),
		'plg_cptags' => array (
			'base' => JPATH_SITE.DS."plugins".DS."content".DS."cptags.php"
		)
	);

	return $dirs;
}
