<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.3 $
* @author Andrea Forghieri & Evgeniy Orlov
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig;
$shLangName 	= '';
$shLangIso 		= '';
$title 			= array ();
$shItemidString = '';
$dosef 			= shInitializePlugin($lang, $shLangName, $shLangIso, $option);
if ($dosef == false)
	return;
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
//$shLangIso = shLoadPluginLanguage( 'com_XXXXX', $shLangIso, '_SEF_SAMPLE_TEXT_STRING');
// ------------------  load language file - adjust as needed ----------------------------------------

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (!empty ($Itemid))
	shRemoveFromGETVarsList('Itemid');
if (!empty ($limit))
	shRemoveFromGETVarsList('limit');
if (isset ($limitstart))
	shRemoveFromGETVarsList('limitstart'); // limitstart can be zero

// start by inserting the menu element title (just an idea, this is not required at all)
$task 	= isset ($task) ? @ $task : null;
$Itemid = isset ($Itemid) ? @ $Itemid : null;
$shCPName = shGetComponentPrefix($option);
$shCPName = empty ($shCPName) ? getMenuTitle($option, $task, $Itemid, null, $shLangName) : $shCPName;
$shCPName = (empty ($shCPName) || $shCPName == '/') ? 'CP' : $shCPName;

switch ($task) {
	case 'tag' :

		if (!empty ($tagName)) {
			$title[] = $task;
			$theTag = split(':', urldecode($tagName));
			$value = $theTag[1];
			$title[] = $value;
			shRemoveFromGETVarsList('tagName');
		} else {
			$dosef = false;
		}
		shRemoveFromGETVarsList('task');
		break;
	default :
		$dosef = false; // these tasks do not require SEF URL
		break;
}

// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef) {
	$string = shFinalizePlugin($string, $title, $shAppendString, $shItemidString, isset ($limit) ? $limit : null, isset ($limitstart) ?  $limitstart : null, isset ($shLangName) ?  $shLangName : null);
}
// ------------------  standard plugin finalize function - don't change ---------------------------

