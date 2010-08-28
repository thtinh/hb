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

// ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Restricted access' );

function com_uninstall() {
	$database = & JFactory::getDBO();
	$base_path = JPATH_ROOT;
	// uninstall Joom!Fish plugin
	if (file_exists(JPATH_ROOT .DS.'administrator'.DS.'components'.DS.'com_joomfish')){
		// delete content elements files from Joom!Fish directory
		@unlink("$base_path/administrator/components/com_joomfish/contentelements/custompropertiesvalues.xml");
		@unlink("$base_path/administrator/components/com_joomfish/contentelements/customproperties.xml");
		@unlink("$base_path/administrator/components/com_joomfish/contentelements/translationCpfieldFilter.php");

		// delete entries in JoomFish!
		$database->setQuery( "DELETE FROM #__jf_tableinfo WHERE joomlatablename='custom_properties_fields'");
		$database->query();
		$database->setQuery( "DELETE FROM #__jf_tableinfo WHERE joomlatablename='custom_properties_values'");
		$database->query();
		$database->setQuery( "DELETE FROM #__jf_content WHERE reference_table='custom_properties_values'");
		$database->query();
		$database->setQuery( "DELETE FROM #__jf_content WHERE reference_table='custom_properties_fields'");
		$database->query();

	}
	return "Component successfully uninstalled.";
}
