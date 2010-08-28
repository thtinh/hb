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

defined('_JEXEC') or die('Restricted access');

JToolBarHelper :: title(JText :: _('About Custom Properties'), 'systeminfo.png');

//reading data from manifest
$data = JApplicationHelper::parseXMLInstallFile( JPATH_COMPONENT_ADMINISTRATOR . DS . 'customproperties.xml');
$version = "1.98.1"; //default
$date = "2009-03-01";
if ( $data['type'] == 'component' )
{
	$version	= $data['version'];
	$date		= $data['creationdate'];
}
?>

	<table class="adminlist" style="width:50%" align="center">
	<tr>
		<td style="width:200px">
			<img src="components/com_customproperties/images/logocp.jpg" alt="Logo Custom Properties"/>
		</td>
		<td>
			<h2>Custom Properties for Joomla! 1.5.x</h2>
			<p>Version <?php echo $version;?>  (<?php echo $date;?>)</p>
			<p>
			2008 &copy; - Andrea Forghieri - Solidsystem. <br/>
			This component is released under the GNU/GPL version 2 License.<br/>
			All copyright statements must be kept.
			</p>
			<p>visit us: <a href="http://www.solidsystem.it">www.solidsystem.it</a></p>
		</td>
	</tr>
		<tr>
			<td style="padding:1em">
				<p style="font-size: 12px;">
					Show your appreciation to the developers.
				</p>
			</td>
			<td>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_new">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="3635093">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="">
				<img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1">
				</form>
			</td>
		</tr>
	<tr>
		<td colspan="2" style="padding:1em">
				<strong>This software is provided "as is," without a warranty of any kind.</strong>
				All express or implied conditions, representations and warranties, including any
				implied warranty of merchantability, fitness for a particular purpose or
				non-infringement, are hereby excluded. Solidsystem shall
				not be liable for any damages suffered by licensee as a result of using,
				modifying or distributing the software or its derivatives. In no event will
				Solidsystem be liable for any lost revenue, profit or data,
				or for direct, indirect, special, consequential, incidental or punitive
				damages, however caused and regardless of the theory of liability, arising
				out of the use of or inability to use software, even if Solidsystem has been
				advised of the possibility of such damages.
		</td>
	</tr>
	<tr>
		<td style="padding:1em">
			Thanks for code contribution to:
		</td>
		<td style="padding:1em">
			Evgeniy Orlov &amp; his brother, Ruben Goethals.
		</td>
	</tr>
	<tr>
		<td style="padding:1em">
			Special thanks to:
		</td>
		<td style="padding:1em">
			conventionconnection.net, joomlawebserver.com, wgnmedia.com,  many suggestions, feature requests, bug reports submitted by cooperative users.
		</td>
	</tr>
	<tr>
		<td style="padding:1em">
			This release would have never seen the light of the day without:
		</td>
		<td style="padding:1em">
			truckloads of Bossanova, Stefano Bollani, Bach, Beethoven, Keith Jarrett, Sergio Cammariere and a generous pour of progressive rock (ELP, King Crimson, Pink Floyd, Genesis)
		</td>
		</tr>
	</table>

