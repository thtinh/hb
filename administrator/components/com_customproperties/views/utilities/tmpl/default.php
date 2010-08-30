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

defined('_JEXEC') or die('Restricted access');

global $option;

$link = "index2.php?option=$option&controller=utilities";

JToolBarHelper::title( JText::_( 'Custom Properties Utilities & Manteinance' ), 'systeminfo.png' );
?>

	<table class="adminform" style="width : 75%;">
	<tr>
		<td style="width: 100px">
			<a href="<?php echo $link."&task=checkdirs";?>">Check directories.</a>
		</td>
		<td>
			<p>Checks if Custom Properties Directories are writable.</p>
		</td>
	</tr>
	<tr>
		<td>
			<a href="<?php echo $link."&task=showce";?>">Manage content elements</a>
		</td>
		<td>
			<p>
				Install / unistall available content elements.
			</p>
		</td>
	</tr>
	<tr>
		<td style="width: 100px">
			<a href="<?php echo $link."&task=instjf";?>">Install Joom!Fish content elements</a>
		</td>
		<td>
			<p>This operation is automatically performed during installation. If JoomFish is installed after Custom Properties you need to run this function to allow CP fields and values translations with JoomFish.</p>
		</td>
	</tr>
	<tr>
		<td style="width: 100px">
			<a href="<?php echo $link."&task=refreshthumbnails";?>">Refresh thumbnails.</a>
		</td>
		<td>
			<p>Remove all automatically generated thumbnails. New thumbnails will be created on later page visits.</p>
		</td>
	</tr>
	<tr>
		<td>
			<a href="<?php echo $link."&task=removecptags";?>">Remove {cptags}</a>
			</td>
			<td>
				<p>
				Delete all occurencies of {cptags} found in content items.
				<br/>Older versions of cptags plugin required '{cptags}' to be embedded into the article to show the custom properties tags.
				Newer versions, while compatible with the older ones, allows to automatically add such tags before or after the article text, thus making {cptags} obsolete (but for special tags placement, i.e. in the middle of the text).<br/>
				This functions saves you the hassle to clean up the articles by hand.
				</p>
			</td>
		</tr>
	</table>