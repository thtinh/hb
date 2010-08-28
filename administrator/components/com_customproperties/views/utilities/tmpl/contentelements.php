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
$available_content_elements = $this->available_content_elements;
$installed_content_elements = $this->installed_content_elements;

$link = "index2.php?option=$option&controller=utilities";

JToolBarHelper :: title(JText :: _('Custom Properties - Show content elements'), 'systeminfo.png');
JToolBarHelper :: back();
?>
		<table class="adminlist" style="width : 40%; float : left; margin :5px;">
			<tr><th colspan="2">Installed content elements</th></tr>
			<tr><th>name</th><th></th></tr>
		<?php

			foreach ($available_content_elements as  $av_ce){
				echo "<tr>" .
						"<td>" . htmlspecialchars($av_ce) . "</td>" .
						"<td><a href=\"$link&amp;task=installce&amp;cename=" . $av_ce . "\">install</a></td>" .
					"</tr>\n";
			}

		?>
		</table>

		<table class="adminlist" style="width : 40%; float:left; margin :5px; ">
			<tr><th colspan="6">Installed Content Elements</th></tr>
			<tr><th>name</th><th>label</th><th>table</th><th>ordering</th><th>active</th><th></th></tr>
		<?php

		foreach ($installed_content_elements as $ce) {
			if($ce->xml == ""){
				echo "<tr><td colspan=\"6\">No content elements installed, only standard content items can be tagged.</tr>\n";
				break;
			}

			echo "<tr>" .
			"<td>" . $ce->name . "</td>" .
			"<td>" . $ce->label . "</td>" .
			"<td>" . $ce->table . "</td>" .
			"<td align=\"center\">" . $ce->ce_ordering . "</td>" .
			"<td align=\"center\">" .
			"<a href=\"$link&amp;task=togglece&amp;cename=" . $ce->name . "\">" .
			"<img border=\"0\" src=\"" . ($ce->active ? 'images/tick.png' : 'images/publish_x.png') . "\"/>" .
			"</a>" .
			"</td>" .
			"<td align=\"center\">" .
			"<a href=\"$link&amp;task=uninstallce&amp;cename=" . $ce->name . "\">remove</a>" .
			"</td>" .
			"</tr>\n";
		}
		?>
		</table>