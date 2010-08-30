<?php
/**
* Joomla Custom Properties
* @package Custom Properties
* @subpackage install.customproperties.php
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
error_reporting(E_ALL ^ E_NOTICE);

/** this function installs language files for installed languages
 * @param string $source_dir relative source directory (eg : /modules/cpsearch/), Requires slashes before and after!
 * @param bool $admin_lang Is administrator language ? default false
 * @param bool $verbose verbose output, echoes the moved and deleted files
 *
 */
function install_language($source_dir, $admin_lang = false, $verbose = false){
	if(empty($source_dir)) return false;

	$dst_dir = $admin_lang ? DS . 'administrator' . DS .'language' . DS :  DS .'language'. DS;
	$mosConfig_absolute_path = JPATH_ROOT;
	$language_files = array();
	$language_files = JFolder::files($mosConfig_absolute_path . $source_dir ,".ini");
	foreach($language_files as $file){
		if(preg_match('/^([^\.]*)?\.??/', $file, $matches)){
			$prefix = $matches[1];
			if(file_exists($mosConfig_absolute_path . DS . "language" . DS . $prefix)){
				@unlink($mosConfig_absolute_path . $dst_dir .$prefix . DS. $file);
				rename($mosConfig_absolute_path . $source_dir .$file,
					$mosConfig_absolute_path . $dst_dir .$prefix . DS. $file );
					if($verbose) echo "install $file <br/>";
			}
			else{
				if($verbose) echo "unlink $file <br/>";
				unlink($mosConfig_absolute_path . $source_dir .$file);
			}
		}
	}
}

function com_install() {

	$database 					= JFactory::getDBO();
	$mosConfig_absolute_path 	= JPATH_ROOT;
	$mosConfig_live_site 		= JURI::base();
	$mosConfig_cachepath 		= JPATH_CACHE;

	//reading data from manifest
	$data = JApplicationHelper::parseXMLInstallFile( JPATH_COMPONENT . DS . 'custompropertiesinstaller.xml');
	$version = "1.98.3.3"; //default
	$date = "2009-06-20";
	if ( $data['type'] == 'component' )
	{
		$version	= $data['version'];
		$date		= $data['creationdate'];
	}

	echo '
		<table class="adminlist" style="width:50%" border="0" align="center">
			<tr>
				<td style="width:200px" align="center">
					<img src="components/com_customproperties/images/logocp.jpg" alt="Logo Custom Properties"/>
				</td>
				<td>
					<h2>Custom Properties</h2>
					<p>Version '.$version.'  ('.$date.')</p>
					<p>
					2008 &copy; - Andrea Forghieri - Solidsystem.<br />
					This component is released under the GNU/GPL version 2 License.<br />
					All copyright statements must be kept.
					</p>
					<p>visit us : <a href="http://www.solidsystem.it">www.solidsystem.it</a></p>
				</td>
			</tr>
			<tr>
				<td style="padding:1em" colspan="2">
					<code>';

	// create tables
	$query ="CREATE TABLE `#__custom_properties_fields` (
					`id` int(11) NOT NULL auto_increment,
					`name` char(50) NOT NULL,
					`label` varchar(255) NOT NULL,
					`field_type` char(50) NOT NULL,
					`modules` varchar(255) NOT NULL,
					`published` tinyint(1) NOT NULL default '0',
					`access` int(11) NOT NULL default '0',
					`ordering` int(11) NOT NULL default '0',
					`checked_out` int(11) NOT NULL default '0',
					PRIMARY KEY  (`id`),
					KEY `state` (`published`),
					KEY `access` (`access`),
					KEY `checked_out` (`checked_out`)
				) ENGINE=MyISAM";
	$database->setQuery($query);
	$database->query();
	echo '<img src="images/tick.png"> Creating custom properties fields table...<br />';

	$query ="CREATE TABLE `#__custom_properties_values` (
				  `id` int(11) NOT NULL auto_increment,
				  `field_id` int(11) NOT NULL default '0',
				  `name` char(50) NOT NULL,
				  `label` varchar(255) NOT NULL,
				  `priority` tinyint(4) NOT NULL default '0',
				  `default` tinyint(1) NOT NULL default '0',
				  `ordering` int(11) NOT NULL default '0',
				  PRIMARY KEY  (`id`),
				  KEY `field_id` (`field_id`)
				) ENGINE=MyISAM";
	$database->setQuery($query);
	$database->query();
	echo '<img src="images/tick.png"> Creating custom properties values table...<br />';

	$query ="CREATE TABLE `#__custom_properties` (
				  `id` int(11) NOT NULL auto_increment,
				  `ref_table` varchar(100) default 'content',
				  `content_id` int(11) NOT NULL default '0',
				  `field_id` int(11) NOT NULL default '0',
				  `value_id` int(11) NOT NULL default '0',
				  PRIMARY KEY  (`id`),
				  UNIQUE KEY `t_c_f_v` (`ref_table`,`content_id`,`field_id`,`value_id`),
				  KEY `content_id` (`content_id`),
				  KEY `cp_field_id` (`field_id`),
				  KEY `cp_value_id` (`value_id`),
				  KEY `ref_table` (`ref_table`)
				) ENGINE=MyISAM";
	$database->setQuery($query);
	$database->query();
	echo '<img src="images/tick.png"> Creating custom properties table...<br />';

	// Delete existing menu - if found
	$database->setQuery( "SELECT id FROM #__components WHERE `option` = 'com_customproperties' AND parent='0' " );
	$oldid = $database->loadResult();
	if(! empty($oldid)){
		echo "<img src=\"images/tick.png\"> Found menu $oldid <br />";
		$database->setQuery( "DELETE FROM #__components " .
				"WHERE `option`= 'com_customproperties'" );
		$database->query();
	}

	// Get menu new id
	$database->setQuery( "SELECT id FROM #__components WHERE `option` = 'com_custompropertiesinstaller' AND parent='0' " );
	$id = $database->loadResult();
	$from = "custompropertiesinstaller";
	$to   = "customproperties";
	// fix menu
	$database->setQuery( "UPDATE #__components SET link = REPLACE(link, '$from', '$to'), " .
			"admin_menu_link = REPLACE(admin_menu_link, '$from', '$to')," .
			"`option` = REPLACE(`option`, '$from', '$to') " .
			"WHERE id = '$id' OR parent = '$id' ");
	$database->query();

	if(! empty($oldid)){
		// upgrading, fixing menu link
		$database->setQuery( "UPDATE #__components SET id = '$oldid' WHERE id = '$id' ");
		$database->query();
		$database->setQuery( "UPDATE #__components SET parent = '$oldid' WHERE parent = '$id' ");
		$database->query();
		echo "<img src=\"images/tick.png\"> Fixing menu id... <br />";
		$id = $oldid;
	}

	// Insert new field 'modules' to DB - upgrade to 0.97
	$database->setQuery( "ALTER TABLE `#__custom_properties_fields` ADD COLUMN `modules` varchar(255) NOT NULL AFTER `field_type`;");
	$database->query();

	// upgrade custom properties to 0.98
	$database->setQuery( "ALTER TABLE #__custom_properties DROP KEY `c_f_v`");
	$database->query();
	$database->setQuery( "ALTER TABLE #__custom_properties ADD  `ref_table` VARCHAR(100) NOT NULL DEFAULT 'content' AFTER `id`");
	$database->query();
	$database->setQuery( "ALTER TABLE #__custom_properties ADD UNIQUE KEY `t_c_f_v` (`ref_table`,`content_id`,`field_id`,`value_id`)");
	$database->query();
	$database->setQuery( "ALTER TABLE #__custom_properties ADD KEY `ref_table` (`ref_table`)");
	$database->query();

	// upgrade custom properties fields to 0.98
	$database->setQuery( "ALTER TABLE #__custom_properties_values ADD `priority` tinyint(4) NOT NULL DEFAULT '0' AFTER `label`");
	$database->query();
	$database->setQuery( "ALTER TABLE #__custom_properties_values ADD  KEY `name` (`name`)");
	$database->query();
	$database->setQuery( "ALTER TABLE #__custom_properties_values ADD  KEY `priority` (`priority`)");
	$database->query();
	echo '<img src="images/tick.png"> Updating database...<br />';

	if(!class_exists('PclZip')){
		include_once($mosConfig_absolute_path . "/administrator/includes/pcl/pclzip.lib.php");
	}
	# before coping , we clean a possibily conflicting directory
	if(file_exists("$mosConfig_absolute_path/administrator/components/com_customproperties/contentelements")){
		JFolder::delete( "$mosConfig_absolute_path/administrator/components/com_customproperties/contentelements" );
	}
	# Install bot_cptags
	echo '<img src="images/tick.png"> Installing Custom Properties Tags content plugin... <br/>';
	$archive = new PclZip($mosConfig_absolute_path . "/components/com_custompropertiesinstaller/bot.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path . "/plugins/content/");
	$database->setQuery("SELECT id FROM #__plugins WHERE element='cptags' AND folder='content'");
	$id = $database->loadResult();
	//register if needed
	if(empty($id)){
		$database->setQuery("REPLACE INTO #__plugins SET name='Custom Properties Tags', element='cptags', folder='content', access=0, ordering='1', published='1'");
		$database->query();
		echo '<img src="images/tick.png"> Registering cptags<br />';
	}
	unset($archive);

	# Install bot_search_cptags
	echo '<img src="images/tick.png"> Installing Custom Properties Tags Search plugin... <br/>';
	$archive = new PclZip($mosConfig_absolute_path . "/components/com_custompropertiesinstaller/search_bot.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path . "/plugins/search/");
	$database->setQuery("SELECT id FROM #__plugins WHERE element='cptags' AND folder='search'");
	$id = $database->loadResult();
	//register if needed
	if(empty($id)){
		$database->setQuery("REPLACE INTO #__plugins SET name='Search - CP Tags', element='cptags', folder='search', access=0, ordering='1', published='1'");
		$database->query();
		echo '<img src="images/tick.png"> Registering search cptags<br />';
	}
	unset($archive);

	# Install bot_cptags.btn
	echo '<img src="images/tick.png"> Installing Custom Properties Tags Button... <br/>';
	$archive = new PclZip($mosConfig_absolute_path . "/components/com_custompropertiesinstaller/btn.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path . "/plugins/editors-xtd/");
	$database->setQuery("SELECT id FROM #__plugins WHERE element='cptags' AND folder = 'editors-xtd' ");
	$id = $database->loadResult();
	//register if needed
	if(empty($id)){
		$database->setQuery("REPLACE INTO #__plugins SET name='Custom Properties Tags Button', element='cptags', folder='editors-xtd', access=0, ordering='1', published='1'");
		$database->query();
		echo '<img src="images/tick.png"> Registering cptags button<br />';
	}
	unset($archive);

	# Install mod_cpsearch
	echo '<img src="images/tick.png"> Installing CP search module... <br/>';
	$archive = new PclZip($mosConfig_absolute_path . "/components/com_custompropertiesinstaller/search.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path . "/modules/mod_cpsearch/");
	// install language files
	install_language("/modules/mod_cpsearch/", false, true);
	$database->setQuery("SELECT id FROM #__modules WHERE module='mod_cpsearch' ");
	$id = $database->loadResult();
	//register if needed
	if(empty($id)){
		$database->setQuery("INSERT INTO #__modules SET title='Custom Properties Search',content='', position='left', module='mod_cpsearch', access='0', ordering='0', published='0'");
		$database->query();
		echo '<img src="images/tick.png"> Registering mod_cpsearch<br />';
	}
	unset($archive);


	# Install mod_cpcloud
	echo '<img src="images/tick.png"> Installing CP cloud module... <br/>';
	$archive = new PclZip($mosConfig_absolute_path . "/components/com_custompropertiesinstaller/cloud.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path . "/modules/mod_cpcloud/");
	// install language files
	install_language("/modules/mod_cpcloud/", false, true);
	$database->setQuery("SELECT id FROM #__modules WHERE module='mod_cpcloud' ");
	$id = $database->loadResult();
	//register if needed
	if(empty($id)){
		$database->setQuery("INSERT INTO #__modules SET title='Custom Properties Cloud',content='', position='left', module='mod_cpcloud', access='0', ordering='0', published='0'");
		$database->query();
		echo '<img src="images/tick.png"> Registering mod_cpcloud<br />';
	}
	unset($archive);

	$cache = JFactory::getCache();
	$cache->clean();

	# Unzip full components files
	echo '<img src="images/tick.png"> Installing components files...<br />';
	$archive = new PclZip($mosConfig_absolute_path . "/components/com_custompropertiesinstaller/com.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path . "/components/com_customproperties/");
	// install language files
	install_language("/components/com_customproperties/", false, true);
    unset($archive);

	# Unzip full admin files
	echo '<img src="images/tick.png"> Installing components admin files...<br />';
	$archive = new PclZip($mosConfig_absolute_path . "/components/com_custompropertiesinstaller/admin.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path . "/administrator/components/com_customproperties/");
	install_language("/administrator/components/com_customproperties/", true, true);
    unset($archive);

	// install Joom!Fish plugin
	if (file_exists($mosConfig_absolute_path . '/administrator/components/com_joomfish')){
		echo '<img src="images/tick.png"/> Joom!Fish detected, installing JF content elements...<br />';
		// move file to Joom!Fish directory
		@copy( "$mosConfig_absolute_path/administrator/components/com_customproperties/jfcontentelements/translationCpfieldFilter.php",
		"$mosConfig_absolute_path/administrator/components/com_joomfish/contentelements/translationCpfieldFilter.php");

		@copy( "$mosConfig_absolute_path/administrator/components/com_customproperties/jfcontentelements/customproperties.xml",
		"$mosConfig_absolute_path/administrator/components/com_joomfish/contentelements/customproperties.xml");

		@copy( "$mosConfig_absolute_path/administrator/components/com_customproperties/jfcontentelements/custompropertiesvalues.xml",
		"$mosConfig_absolute_path/administrator/components/com_joomfish/contentelements/custompropertiesvalues.xml");
	}


	/* booklibrary CP content element */
	if (file_exists($mosConfig_absolute_path . '/administrator/components/com_booklibrary')){
		echo '<img src="images/tick.png"/> Booklibrary detected, installing booklibrary content elements...<br />';
		@copy( "$mosConfig_absolute_path/administrator/components/com_customproperties/samplece/booklibrary.xml",
		"$mosConfig_absolute_path/administrator/components/com_joomfish/contentelements/booklibrary.xml");
	}
	/* docman CP content element */
	if (file_exists($mosConfig_absolute_path . '/administrator/components/com_docman')){
		echo '<img src="images/tick.png"/> Docman, installing docman content elements...<br />';
		@copy( "$mosConfig_absolute_path/administrator/components/com_customproperties/samplece/docman.xml",
		"$mosConfig_absolute_path/administrator/components/com_joomfish/contentelements/docman.xml");
	}
	/* Phoca Gallery CP content element */
	if (file_exists(JPATH_ROOT. '/administrator/components/com_phocagallery')){
		echo '<img src="images/tick.png"/> Phoca Gallery, installing phocagallery content elements...<br />';
		@copy( JPATH_ROOT."/administrator/components/com_customproperties/samplece/phocagallery.xml",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/phocagallery.xml");
		@copy( JPATH_ROOT."/administrator/components/com_customproperties/samplece/phocagallery.xml",
		JPATH_ROOT."/administrator/components/com_joomfish/contentelements/phocacategory.xml");
	}



	echo '<img src="images/tick.png"> Loading default configurations...<br /><br/>
					</code>
				</td>
			</tr>
			<tr>
				<td style="padding:1em" colspan="2" align="center">
					<strong>Component successfully installed/updated</strong><br/>
				</td>
			</tr>
			<tr>
				<td style="padding:1em" colspan="2" align="center">
					<h2>Ignore error messages (if any), review and <em>save</em> component, modules and plugin parameters.</h2>
				</td>
			</tr>
		</table>';

	// Clear cache
	$file_list = JFolder::files($mosConfig_cachepath ,".xml");
	foreach ($file_list as $val) {
		if (strstr($val, "cache_")){
			@unlink($mosConfig_cachepath . "/" . $val);
		}
	}


	$cache = JFactory::getCache();
	$cache->clean();

	// remove temporary directory
	JFolder::delete( "$mosConfig_absolute_path/components/com_custompropertiesinstaller" );
	JFolder::delete( "$mosConfig_absolute_path/administrator/components/com_custompropertiesinstaller" );

	return "Component successfully installed.";
}

