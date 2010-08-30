<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.5 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * Generates an HTML select list of Custom Properties modules
 *
 * @param	mixed	The keys that are selected (accepts an array or a comma separated list of modules id)
 * @returns	string	HTML for the select list
 */
function cpModulesList($module_list = ''){

  $database = & JFactory::getDBO();
  $query = 'SELECT * FROM #__modules '
    . 'WHERE module = \'mod_cpsearch\''
    . 'ORDER BY title ';

  $database->setQuery($query);
  $module_names = $database->loadObjectList();

  if(!is_array($module_list)){
    $selected_modules = split(',', $module_list );
  }

  $output = "<select multiple=\"multiple\" size=\"".(count($module_names) + 1)."\" name=\"modules[]\">\n";

  $selected = ($module_list ? "" : "selected=\"selected\"");
  $output .= "<option value=\"\" $selected >All</option>\n";
  foreach($module_names as $module){
    $selected = in_array($module->id, $selected_modules) ? "selected=\"selected\"" : "";
    $output .= "<option value=\"{$module->id}\" $selected>{$module->title}</option>\n";
  }
  $output .= "</select>\n";

  return $output;
}

/**
 * Load the javascript for adding new rows
 * @returns void
 */
function linkAddRowJavascript(){
	$document = JFactory::getDocument();
	$script = JUri::root().'/administrator/components/com_customproperties/includes/customproperties.js';
	$document->addScript($script);
	return;
}
/**
 * Write the javascript for adding new rows
 * With some versions of PHP the upper funcion does not work, so
  * we need to embed the javascript  instead of linking it.
 * @returns void
 */
function embedAddRowJavascript(){
	jimport('joomla.filesystem.file');
	$scriptfile = JPATH_COMPONENT_ADMINISTRATOR .DS. 'includes'. DS. 'customproperties.js';
	if($script = JFile::read($scriptfile)){
		echo "<script type=\"text/javascript\">$script</script>\n";
	}
	else{
		echo "<span class=\"error\">Error: Can't include javascript file customproperties.js</span>";
	}
	return;
}

/** retrieve tags for showning in Content Item List. Returns unpublished tags too.
* @param $cid = content item id
* @param object cpContenetElement $content_element content element to retrieve the tags from
* Returns a table : 1 tag per row or false if no tags are found
*/
function getTags($cid, $content_element){
	$database = JFactory::getDBO();
	$result = "";
	$query = "SELECT DISTINCT f.id as fid, f.label as name, v.id as vid, v.label
		FROM #__custom_properties AS cp
			INNER JOIN #__custom_properties_fields AS f
			ON (cp.field_id = f.id )
			INNER JOIN #__custom_properties_values AS v
			ON (cp.value_id = v.id )
		WHERE cp.content_id = '$cid'
		AND cp.ref_table = '".$content_element->table."'
		ORDER BY f.ordering, v.ordering
		";

	$database->setQuery($query);
	$tags = $database->loadObjectList();

	if(count($tags) == 0) return false;

	$result = "<table>";
	foreach($tags as $tag){
		$result .= "<tr><td>".$tag->name.":".$tag->label."</td></tr>";
	}
	$result .= "</table>";
	return $result;
}

/**
* recursive_remove_directory( directory to delete, empty )
* expects path to directory and optional TRUE / FALSE to empty
* of course PHP has to have the rights to delete the directory
* you specify and all files and folders inside the directory
* @param string $directory directory to be emptied  /removed
* @param bool $empty if tru, the directory is emptied
* examples
* to use this function to totally remove a directory, write:
* recursive_remove_directory('path/to/directory/to/delete');
*
* to use this function to empty a directory, write:
* recursive_remove_directory('path/to/full_directory',TRUE);
*/

function recursive_remove_directory($directory, $empty=FALSE)
{
	// if the path has a slash at the end we remove it here
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}

	// if the path is not valid or is not a directory ...
	if(!file_exists($directory) || !is_dir($directory))
	{
		// ... we return false and exit the function
		return FALSE;

	// ... if the path is not readable
	}elseif(!is_readable($directory))
	{
		// ... we return false and exit the function
		return FALSE;

	// ... else if the path is readable
	}else{

		// we open the directory
		$handle = opendir($directory);

		// and scan through the items inside
		while (FALSE !== ($item = readdir($handle)))
		{
			// if the filepointer is not the current directory
			// or the parent directory
			if($item != '.' && $item != '..')
			{
				// we build the new path to delete
				$path = $directory.'/'.$item;

				// if the new path is a directory
				if(is_dir($path))
				{
					// we call this function with the new path
					recursive_remove_directory($path);

				// if the new path is a file
				}else{
					// we remove the file
					unlink($path);
				}
			}
		}
		// close the directory
		closedir($handle);

		// if the option to empty is not set to true
		if($empty == FALSE)
		{
			// try to delete the now empty directory
			if(!rmdir($directory))
			{
				// return false if not possible
				return FALSE;
			}
		}
		// return success
		return TRUE;
	}
}
