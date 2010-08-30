<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.2 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die('Restricted access');

//$base_path = JPATH_BASE;
$elements = $this->dirs;


JToolBarHelper::title( JText::_( 'Custom Properties - Check Directories' ), 'systeminfo.png' );
JToolBarHelper::back();
?>

		<table class="adminform" style="width : 75%;">
		<?php

		foreach ($elements as $name => $element) {
			echo "<tr><th colspan=\"3\">$name</th></tr>\n";
			foreach ($element as $key => $dir) {
				if (is_dir($dir)) {
					if (is_writable($dir)) {
						echo "<tr><td>$key</td><td>$dir</td><td>writable</td>";
					} else {
						echo "<tr><td>$key</td><td>$dir</td><td>not writable</td>";
					}
				}
				elseif (is_file($dir)) {
					echo "<tr><td>$key</td><td>$dir</td><td>installed</td>";
				}
			}
		}

		echo "</table>\n";