<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.6 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install() {

	// install Joom!Fish plugin
	$got_JoomFish = file_exists(JPATH_ROOT .DS.'administrator'.DS.'components'.DS.'com_joomfish') ? 1 : 0 ;
	if ($got_JoomFish){
		echo '<img src="images/tick.png"/> Joom!Fish detected, installing JF content elements...<br />';
		// move file to Joom!Fish directory
		@copy( JPATH_ROOT."/administrator/components/com_customproperties/jfcontentelements/translationCpfieldFilter.php",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/translationCpfieldFilter.php");

		@copy( JPATH_ROOT."/administrator/components/com_customproperties/jfcontentelements/customproperties.xml",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/customproperties.xml");

		@copy( JPATH_ROOT."/administrator/components/com_customproperties/jfcontentelements/custompropertiesvalues.xml",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/custompropertiesvalues.xml");

	}

	/* booklibrary CP content element */
	if (file_exists(JPATH_ROOT . '/administrator/components/com_booklibrary')){
		echo '<img src="images/tick.png"/> Booklibrary detected, installing booklibrary content elements...<br />';
		@copy( JPATH_ROOT."/administrator/components/com_customproperties/samplece/booklibrary.xml",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/booklibrary.xml");
	}
	/* docman CP content element */
	if (file_exists(JPATH_ROOT. '/administrator/components/com_docman')){
		echo '<img src="images/tick.png"/> Docman, installing docman content elements...<br />';
		@copy( JPATH_ROOT."/administrator/components/com_customproperties/samplece/docman.xml",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/docman.xml");
	}
	/* Phoca Gallery CP content element */
	if (file_exists(JPATH_ROOT. '/administrator/components/com_phocagallery')){
		echo '<img src="images/tick.png"/> Phoca Gallery, installing phocagallery content elements...<br />';
		@copy( JPATH_ROOT."/administrator/components/com_customproperties/samplece/phocagallery.xml",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/phocagallery.xml");
	}

	return "Component successfully installed.";
}

?>
