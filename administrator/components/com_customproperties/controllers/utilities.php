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
 * Custom Properties Component Controller - Utilities
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesControllerUtilities extends JController {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {

		parent :: __construct();

		$this->registerTask('togglece', 	'toggleContentElement');
		$this->registerTask('installce', 	'installContentElement');
		$this->registerTask('uninstallce', 	'uninstallContentElement');
		$this->registerTask('instjf', 		'installJFContentElements');

	}

	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	function display() {
		switch ($this->getTask()) {
			case 'checkdirs' :
				JRequest :: setVar('layout', 'checkdirs');
				JRequest :: setVar('view', 'utilities');
				break;
			case 'showce' :
			case 'installce' :
			case 'uninstallce' :
			case 'togglece' :
				JRequest :: setVar('layout', 'contentelements');
				JRequest :: setVar('view', 'utilities');
				break;
			default :
				JRequest :: setVar('view', 'utilities');
				break;
		}
		parent :: display();
	}

	/** toggle content element state (active / disabled) */
	function toggleContentElement() {

		global $option;
		$ce_name = JRequest :: getVar('cename', '');
		$return_to = "index2.php?option=$option&controller=utilities&task=showce";

		if (!$ce = getContentElementByName($ce_name, true))
			return $this->setRedirect($return_to, "$ce Error, wrong content element name.");

		if (!$xml = $ce->xml)
			$this->setRedirect($return_to, "Error, no XML file.");
		;

		require_once (JPATH_SITE . "/includes/domit/xml_domit_lite_include.php");
		$xmlDoc = & new DOMIT_lite_Document();
		$xmlDoc->resolveErrors(true);

		if ($xmlDoc->loadXML($xml, false, true)) {
			$element = & $xmlDoc->documentElement;
			if ($element->getTagName() == 'contentelement') {

				if ($element->hasAttribute("active")) {

					$curr_state = $element->getAttribute("active");
					$element->setAttribute("active", $curr_state == 0 ? '1' : '0');
					$xmlDoc->saveXML($xml, true);

				}
			}
		}
		$this->setRedirect($return_to, "Done");
	}

	/** install content element (moves file from samplece to contentelements) */
	function installContentElement() {

		global $option;
		$ce_name = JRequest :: getVar('cename', '');
		$return_to = "index2.php?option=$option&controller=utilities&task=showce";

		if (file_exists(JPATH_ROOT . '/administrator/components/com_customproperties/samplece/' . $ce_name . ".xml")) {

			if(copy(JPATH_ROOT . "/administrator/components/com_customproperties/samplece/" . $ce_name . ".xml",
				JPATH_ROOT . "/administrator/components/com_customproperties/contentelements/" . $ce_name . ".xml")){
					$this->setRedirect($return_to, "Done");
			}
			else{
				$this->setRedirect($return_to, "Unable to copy $ce_name" . ".xml", 'error');
			}
		}
		else{
			$this->setRedirect($return_to, $ce_name . ".xml not found" , 'error');
		}


	}
	/** uninstall content element */
	function uninstallContentElement() {

		global $option;
		$ce_name = JRequest :: getVar('cename', '');
		$return_to = "index2.php?option=$option&controller=utilities&task=showce";

		if (!$ce = getContentElementByName($ce_name, true))
			return $this->setRedirect($return_to, "$ce Error, wrong content element name.");

		if (!$xml = $ce->xml)
			$this->setRedirect($return_to, "Error, no XML file.");
		;


		if(unlink($ce->xml)){
				$this->setRedirect($return_to, "Done");
			}
			else{
				$this->setRedirect($return_to, "Unable to remove $ce_name" . ".xml", 'error');
			}


	}


	/** (re)install Joomfish content elements */
	function installJFContentElements() {

		global $option;
		$return_to = "index2.php?option=$option&controller=utilities";
		// install Joom!Fish plugin
		$got_joomfish = false;
		if (file_exists(JPATH_ROOT . '/administrator/components/com_joomfish')) {
			$got_joomfish = true;
			// copy  file to Joom!Fish directory
			@copy(JPATH_ROOT . "/administrator/components/com_customproperties/jfcontentelements/translationCpfieldFilter.php", JPATH_ROOT . "/administrator/components/com_joomfish/contentelements/translationCpfieldFilter.php");

			@copy(JPATH_ROOT . "/administrator/components/com_customproperties/jfcontentelements/custom_properties_fields.xml", JPATH_ROOT . "/administrator/components/com_joomfish/contentelements/custom_properties_fields.xml");

			@copy(JPATH_ROOT . "/administrator/components/com_customproperties/jfcontentelements/custom_properties_values.xml", JPATH_ROOT . "/administrator/components/com_joomfish/contentelements/custom_properties_values.xml");
		}

		if ($got_joomfish) {
			$this->setRedirect($return_to, "Done, JoomFish content elements reinstalled.");
		} else {
			$this->setRedirect($return_to, "JoomFish not found!" . JPATH_ROOT, 'error');
		}

	}

	/**
	 * deletes all automaticclly generated thumbnails
	 */
	function refreshThumbnails() {
		global $option;
		$return_to = "index2.php?option=$option&controller=utilities";

		$basepath = JPATH_ROOT;
		$thumbPath = $basepath . DS. 'components'. DS .'com_customproperties' .DS . 'images';
		$totfiles = 0;
		$totdirs = 0;
		if ($handle = opendir($thumbPath)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && $file != "index.html") {
					if (is_file($thumbPath . DS . $file)) {
						unlink($thumbPath . DS . $file);
						$totfiles++;
					}
					elseif (is_dir($thumbPath . DS . $file)) {
						recursive_remove_directory($thumbPath . DS . $file, FALSE);
						$totdirs++;
					}
				}
			}
			closedir($handle);
		}

		$this->setRedirect($return_to, "Done: deleted $totfiles files and $totdirs directories.");

	}

	/** this function removes all {cptags} from content items */
	function removeCptags() {

		global $option;
		$return_to = "index2.php?option=$option&controller=utilities";

		$database = &JFactory::getDBO();

		$query = "UPDATE #__content
					SET introtext = REPLACE(introtext, '{cptags}','')
					WHERE `introtext` LIKE '%{cptags}%' ";
		$database->setQuery($query);
		$database->query();

		$query = "UPDATE #__content
					SET fulltext = REPLACE(fulltext, '{cptags}','')
					WHERE `fulltext` LIKE '%{cptags}%' ";
		$database->setQuery($query);
		$database->query();

		$this->setRedirect($return_to, "Done.");

	}

}