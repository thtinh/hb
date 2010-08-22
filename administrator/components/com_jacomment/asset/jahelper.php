<?php
/*
# ------------------------------------------------------------------------
# JA Comments component for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2004-2010 JoomlArt.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2. CSS / JS are Copyrighted Commercial,
# bound by Proprietary License of JoomlArt. For details on licensing, 
# Please Read Terms of Use at http://www.joomlart.com/terms_of_use.html.
# Author: JoomlArt.com
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# Redistribution, Modification or Re-licensing of this file in part of full, 
# is bound by the License applied. 
# ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * Enter description here...
 *
 */
$GLOBALS ['javconfig'] = array ();
// Component Helper
jimport('joomla.application.component.helper');

$GLOBALS ['JVVERSION'] = '1.0.0';
$GLOBALS ['JVPRODUCTKEY'] = 'COM_JAVOICE';

class JAVoiceHelpers {
	/**
	 * Enter description here...
	 * giapnd add
	 * @return unknown
	 */
	function isPostBack() {
		if (JRequest::getVar ( 'task' ) == 'add')
			return FALSE;
		return count ( $_POST );
	}
	/**
	 * giapnd add
	 */
	function generatDate($timestamp, $mid = 0, $format = "d/M/Y H:i:s") {
		if (intval ( $timestamp ) == 0) {
			return "<span class=\"small\"> ". JText::_('not available')."</span>";
		}
		$cal = explode ( " ", date ( $format, $timestamp ) );
		if ($mid != 0) {
			if ($cal [0] == date ( "d/M/Y" )) {
				return JText::_("Today");
			} else {
				return $cal [0];
			}
		} else {
			return $cal [0] . " ".JText::_('at')." " . $cal [1];
		}
	}
	/**
	 * giapnd add
	 * return path template current
	 */
	function checkFileTemplate($file,$type='css',$folder=''){
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$tBaseDir = $client->path.DS.'templates';		
		$template=JAVoiceHelpers::templateDefaulte();
		$fileName='';
		if($template){
			$tBaseDir.=DS.$template->name;
			$fileName=$tBaseDir.DS.$type.DS;
			if($folder)$fileName.=$folder.DS.'tmpl';
			$fileName.=DS.$file;
			if(!JFile::exists($fileName))
				return FALSE;
		}		
		return $fileName;
	}	
	function templateDefaulte(){
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$tBaseDir = $client->path.DS.'templates';
		//get template xml file info
		$rows = array();
		$rows = JAVoiceHelpers::parseXMLTemplateFiles($tBaseDir);	
		$template='';
 		for($i = 0; $i < count($rows); $i++)  {
			if(JAVoiceHelpers::isTemplateDefault($rows[$i]->directory, $client->id))
				$template=$rows[$i];
		}	
		return $template;	
	}
	function parseXMLTemplateFiles($templateBaseDir)
	{
		// Read the template folder to find templates
		jimport('joomla.filesystem.folder');
		$templateDirs = JFolder::folders($templateBaseDir);

		$rows = array();

		// Check that the directory contains an xml file
		foreach ($templateDirs as $templateDir)
		{
			if(!$data = JAVoiceHelpers::parseXMLTemplateFile($templateBaseDir, $templateDir)){
				continue;
			} else {
				$rows[] = $data;
			}
		}

		return $rows;
	}
	function isTemplateDefault($template, $clientId)
	{
		$db =& JFactory::getDBO();

		// Get the current default template
		$query = ' SELECT template '
				.' FROM #__templates_menu '
				.' WHERE client_id = ' . (int) $clientId
				.' AND menuid = 0 ';
		$db->setQuery($query);
		$defaultemplate = $db->loadResult();

		return $defaultemplate == $template ? 1 : 0;
	}
	function parseXMLTemplateFile($templateBaseDir, $templateDir)
	{
		// Check of the xml file exists
		if(!is_file($templateBaseDir.DS.$templateDir.DS.'templateDetails.xml')) {
			return false;
		}

		$xml = JApplicationHelper::parseXMLInstallFile($templateBaseDir.DS.$templateDir.DS.'templateDetails.xml');

		if ($xml['type'] != 'template') {
			return false;
		}

		$data = new StdClass();
		$data->directory = $templateDir;

		foreach($xml as $key => $value) {
			$data->$key = $value;
		}

		$data->checked_out = 0;
		$data->mosname = JString::strtolower(str_replace(' ', '_', $data->name));

		return $data;
	}	
	function temp_export($item) {
		$content = '## ************** ' . JText::_ ( 'Begin email template' ) . ': ' . $item ['name'] . ' ****************##' . "\r\n\r\n";
	
		$content .= '[Email_Template name="' . $item ['name'] . '"';
	
		$content .= ' published="' . $item ['published'] . '" group="' . ( int ) $item ['group'] . '" language="' . $item ['language'] . '"]' . "\r\n";
		
		$content .= '[title]' . "\r\n";
		$content .= $item ['title'] . "\r\n";
		
		$content .= '[subject]' . "\r\n";
		$content .= $item ['subject'] . "\r\n";
		
		$content .= '[content]' . "\r\n";
		$content .= $item ['content'] . "\r\n";
		
		$content .= '[EmailFromName]' . "\r\n";
		$content .= $item ['email_from_name'] . "\r\n";
		
		$content .= '[EmailFromAddress]' . "\r\n";
		$content .= $item ['email_from_address'] . "\r\n";
		$content .= '[/Email_Template]' . "\r\n\r\n";
		$content .= '## ************** ' . JText::_ ( 'End email template' ) . ': ' . $item ['name'] . ' ****************##' . "\r\n\r\n\r\n\r\n\r\n\r\n";
		
		return $content;
	}
	function getGroupUser($where='',$name='',$attr='',$selected='',$default=0){
		$db = JFactory::getDBO();
		$query = 'SELECT name AS value, name AS text'
			. ' FROM #__core_acl_aro_groups'
			. ' WHERE name != "ROOT"'
			. ' AND name != "USERS" '
			. $where
		;
		$db->setQuery( $query );
		$types = $db->loadObjectList();

		if($default){
			$types 	= array_merge ( array (JHTML::_ ( 'select.option', '0', JText::_ ( '-Select Group-' ), 'value', 'text' ) ), $types );
		}		
		
		$lists = JHTML::_ ( 'select.genericlist', $types, $name, $attr, 'value', 'text', $selected );
		
		return $lists;
	}
	function displayNote($message,$type){
		?>
		<div id="jav-system-message">
					<?php echo $message;?>
		</div>		
		<script>
		jQuery(document).ready( function($) {
			var coo = getCookie('hidden_message_<?php echo $type?>');
			if(coo==1)
				$('#jav-system-message').attr('style','display:none');
			else
				$('#jav_help').html('<?php echo JText::_('[Close]')?>');
		});	
		</script>		
		<?php 	
	}
	/**
	 * end giapnd add
	 */
	/**
	 * Enter description here...
	 *
	 */
	function get_config_system() {
		global $mainframe, $javconfig;
				
		if (defined ( 'COMPOENT_JAVOICE_CONFIG' ))
			return;
		
		$setup = new stdClass ( );
		$db = JFactory::getDBO ();
		$setup = new stdClass ( );
		$q = 'SELECT * FROM #__jav_configs';
		$db->setQuery ( $q );
		$rows = $db->loadObjectList ();
		if ($rows) {
			foreach ( $rows as $row ) {
				$javconfig [$row->group] = new JParameter ( $row->data );
			}
		}

		define ( 'COMPOENT_JAVOICE_CONFIG', true );
	}
	
	/* Enter description here...
	 *
	 * @param unknown_type $timeStamp
	 * @param unknown_type $mid
	 * @return unknown
	 */
	function generatTimeStamp($timeStamp, $mid = 0) {
		$ago = 0;
		if ($mid == 0) {
			$cal = (time () - $timeStamp);
		} else {
			$cal = ($timeStamp - time ());
			if ($cal < 0) {
				$cal = 0 - $cal;
				$ago = 1;
			}
		}
		$d = floor ( $cal / 24 / 60 / 60 );
		$h = floor ( ($cal / 60 / 60 - $d * 24) );
		$m = floor ( ($cal / 60 - $d * 24 * 60 - $h * 60) );
		
		if ($mid == 0) {
			if ($d < 3) {
				$str = "<span class=\"small\">" . ($h+$d*24) . "h ago</span>";
			} /*elseif ($d == 1) {
				$str = "<span class=\"class_yesterday\">" . JText::_ ( 'Yesterday' ) . " " . "</span><span class=\"small\"> +" . $h . "h</span>";
			} elseif ($d == 2) {
				$str = "<span class=\"class_2dayago\">2 " . JText::_ ( 'days' ) . " " . JText::_ ( 'ago' ) . "</span>";
			} else {
				//$str = generatDate($timeStamp,1);
				$str = "<span class=\"time_show\">" . $d . "d," . $h . "h " . JText::_ ( 'ago' ) . ".</span>";
			}*/
			elseif($d<120){
				$str = "<span class=\"class_2dayago\"> ". $d. JText::_ ( 'days' ) . " " . JText::_ ( 'ago' ) . "</span>";
			}
			else{
				$str = "<span class=\"time_show\"> ". $m. JText::_ ( 'months' ) . " " . JText::_ ( 'ago' ) . "</span>";
			}
			return $str;
		} else {
			if ($d == 0) {
				$str = "<span class=\"class_today\">" . JText::_ ( 'Today' ) . "</span>";
			} else {
				if ($ago == 1) {
					if ($d == 1) {
						$str = "<span class=\"class_yesterday\">" . JText::_ ( 'Yesterday' ) . "<span class=\"small\"> +" . $h . "h</span>";
					
					} else {
						//$str = generatDate($timeStamp,1);
						$str = "<span class=\"time_show\">" . $d . "d," . $h . "h " . JText::_ ( 'ago' ) . ".</span>";
					}
				} else {
					if ($d == 1) {
						$str = "<span class=\"class_tomorrow\">" . JText::_ ( 'Tomorrow' ) . "</span>";
					} else {
						//$str = generatDate($timeStamp,1);
						$str = "<span class=\"time_show\">" . $d . "d," . $h . "h.</span>";
					}
				}
			}
			return $str;
		}
	}
	
	function check_access() {
		global $mainframe, $javconfig;
		
		$access = isset($javconfig['systems'])?$javconfig['systems']->get('access', 0):0;
		$user = & JFactory::getUser ();
		
		// Check to see if the user has access to view the full article
		$aid = $user->get ( 'aid' );
		
		if ($access <= $aid) {
			JError:: raiseNotice( 1, JText::_('The component is currently offline to the public.') );
			return true;
		} else {
			if (! $aid) {
				// Redirect to login
				$uri = JFactory::getURI ();
				$return = $uri->toString ();
				
				$url = 'index.php?option=com_user&view=login';
				$url .= '&return=' . base64_encode ( $return );
				;
				
				//$url	= JRoute::_($url, false);
				$mainframe->redirect ( $url, JText::_ ( 'You must login first' ) );
			} else {
				if(isset($javconfig['systems'])){
					$msg = JText::_(@$javconfig['systems']->get('display_message', 'This site is down for maintenance. Please check back again soon.'));
				}	
				else{
					$msg = JText::_('This site is down for maintenance. Please check back again soon.');
				}
				JError::raiseWarning ( 403, $msg );
				return false;
			}
		}
	}
	
	function parse_JSON($objects) {
		if (! $objects)
			return;
		$db = JFactory::getDBO ();
		
		$html = '';
		$item_tem = array ();
		foreach ( $objects as $i => $row ) {
			$tem = array ();
			$item_tem [$i] = '{';
			foreach ( $row as $k => $value ) {
				//$value = $db->Quote($value);
				$tem [$i] [] = "'$k' : " . $db->Quote ( $value ) . "";
			}
			$item_tem [$i] .= implode ( ',', $tem [$i] );
			$item_tem [$i] .= '}';
		}
		
		if ($item_tem)
			$html = implode ( ',', $item_tem );
		
		return $html;
	}
	function parseProperty($type = 'html', $id = 0, $value = '',$reload=0) {
		$object = new stdClass ( );
		$object->type = $type;
		$object->id = $id;
		$object->value = $value;
		if($reload)$object->reload=$reload;
		return $object;
	}
	function parsePropertyPublish($type = 'html', $id = 0,$publish=0,$number=0,$function='publish',$title='Publish',$un='Unpublish') {
		$object = new stdClass ( );
		$object->type = $type;
		$object->id = $id;
		if(!$publish){
			$html = '<a  href="javascript:void(0);" onclick="return listItemTask(\'cb'.$number.'\',\''.$function.'\')" title=\''.$title.'\'><img id="i5" border="0" src="images/publish_x.png" alt="Publish"/></a>';
		}
		else {
			$function='un'.$function;
			$html = '<a  href="javascript:void(0);" onclick="return listItemTask(\'cb'.$number.'\',\''.$function.'\')" title=\''.$un.'\'><img id="i5" border="0" src="images/tick.png" alt="Unpublish"/></a>';
		}
					
		$object->value = $html;
		return $object;
	}
	function message($iserror = 1, $messages) {
		if ($iserror){
			$content = '
					<dd class="error message fade">
						<ul id="jav-error">';
			foreach ($messages as $message){
				$content.='<li>' . $message . '</li>';
			}
			$content.='			</ul>
					</dd>';
		}
		else{
			$content = '<dt class="message">Message</dt>
						<dd class="message message fade">
						<ul>';
			if ($messages && is_array($messages)){
				foreach ($messages as $message){
					$content.='<li>' . $message . '</li>';
				}
			}
			else {
				$content.='<li>' . $messages . '</li>';
			}
			$content.='			</ul>
					</dd>';
		}
		return $content;
	}	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $template
	 * @return unknown
	 */
	function getEmailTemplate($temp_name) {
		
		$db=JFactory::getDBO();
		
		$client = & JApplicationHelper::getClientInfo ( 0 );
		$params = JComponentHelper::getParams ( 'com_languages' );
		$language = $params->get ( $client->name, 'en-GB' );
		
		$query = "SELECT * FROM #__jav_email_templates WHERE name='$temp_name' and language='$language' and  published=1";
		$db->setQuery ( $query );
		$template = $db->loadObject ();
		
		if (! $template && $language != 'en-GB') {
			$query = "SELECT * FROM #__jav_email_templates WHERE name='$temp_name' and language='en-GB' and  published=1";
			$db->setQuery ( $query );
			$template = $db->loadObject ();
		}
		return $template;
	}
	function getFilterConfig(){
		global $javconfig,$mainframe;	
		$filters['{CONFIG_ROOT_URL}']= $mainframe->getCfg ('live_site');
		$filters['{CONFIG_SITE_TITLE}'] = $mainframe->getCfg ('live_site');
		$filters['{ADMIN_EMAIL}'] = $javconfig['systems']->get('fromemail');
		$filters['{SITE_CONTACT_EMAIL}'] = $javconfig['systems']->get('fromemail');
		return $filters;	
	}
	function getLink($link,$title='')
	{
		if (!strpos('http://',$link)) {
			$link=substr($link,1,strlen($link));
			$link = JURI::root().$link;
		}
		if($title!='')$link="<a href='$link'>$title</a>";
		return $link;
	}	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $to
	 * @param unknown_type $nameto
	 * @param unknown_type $subject
	 * @param unknown_type $content
	 * @param unknown_type $filters
	 * @param unknown_type $from
	 * @param unknown_type $fromname
	 * @param unknown_type $attachment
	 * @param unknown_type $header
	 * @return unknown
	 */
	function sendmail($to, $nameto, $subject, $content, $filters = "", $from = '', $fromname = '', $attachment = array(), $header = true) {
		global $javconfig;
		
		if ($header) {
			$header = $this->getEmailTemplate ( "mailheader" );
			$footer = $this->getEmailTemplate ( "mailfooter" );
			if ($header)
				$content = $header->content . "\n" . $content . "\n\n";
			if ($footer) {
				$content .= $footer->content;
			}
		}

		if (is_array ( $filters )) {
			foreach ( $filters as $key => $value ) {
				$subject = str_replace ( $key, $value, $subject );
				$content = str_replace ( $key, $value, $content );
			}
		}
		
		$content = stripslashes ( $content );
		$subject = stripslashes ( $subject );
		
		if (! $from)
			$from = $javconfig ['systems']->get ( 'fromemail' );
		if (! $fromname)
			$fromname = $javconfig ['systems']->get ( 'fromname' );
		$sendmail = $javconfig['systems']->get('enabled');
		$mail = null;
		if ($sendmail == 2) {
			//echo mail
			if(is_array($to)) $to = implode(', ', $to);
			echo JText::_("Sender:") .' '. $fromname . ' (' . $from . ")" . "<br />";
			echo JText::_("Send to: ") .' '. $nameto . ' (' .$to . ")" . "<br />";
			echo JText::_("Subject:") .' '. $subject . "<br />";
			echo JText::_('Content:') .' ' . str_replace ( "\n", "<br/>", $content ) . "<br />-----------------------------<br />";			
			return true;
		} elseif ($sendmail == 1) {
			//send email
			$mail = JFactory::getMailer ();
			$mail->setSender ( array ($from, $fromname ) );
			$mail->addRecipient ( $to );
			$mail->setSubject ( $subject );
			$mail->setBody ( str_replace ( "\n", "<br/>", $content ) );
			
			if ($javconfig ['systems']->get ( 'sendmode' ))
				$mail->IsHTML ( true );
			else
				$mail->IsHTML ( false );
			
			if ($javconfig ['systems']->get ( 'ccemail' ) != "")
				$mail->addCc ( explode ( ',', $javconfig ['systems']->get ( 'ccemail' ) ) );
			
			if ($attachment)
				$mail->addAttachment ( $attachment );
				//
			$sent = $mail->Send ();
			if ($mail->ErrorInfo != '')	return false;
			return $sent;
		}
		return false;
	}
	
	/**
	 * This function validate one email address.
	 * $email           Email to validate.
	 * return   1 if this email is valid, 0 otherwise.
	 */
	function validate_email($email) {
		// Create the syntactical validation regular expression
		$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		
		// Presume that the email is invalid
		$valid = 0;
		
		// Validate the syntax
		if (eregi ( $regexp, $email )) {
			$valid = 1;
		} else {
			$valid = 0;
		}
		
		return $valid;
	
	}
	
	function checkPermissionAdmin(){
		global $javconfig;
		$user = JFactory::getUser();
		$permissions = isset($javconfig['permissions'])?$javconfig['permissions']:null;
		
		if(isset($javconfig['permissions'])){
			$permissions = $javconfig['permissions']->get('permissions');
			$permissions = explode(',', $permissions );//print_r($permissions);exit;
			if(in_array($user->id, $permissions ) && $user->id) return true;
			else return false;
		}
		else{
			
			if( in_array($user->usertype, array('Manager', 'Administrator', 'Super Administrator'))){
				return true;
			}
		}
		return false;
	}
	
/**
	 * Enter description here...
	 *
	 * @param unknown_type $URL
	 * @param unknown_type $req
	 * @return unknown
	 */
	function curl_getdata($URL, $req) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_URL, $URL );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt ( $ch, CURLOPT_POST, TRUE );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $req );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		
		return $result;
	
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $host
	 * @param unknown_type $path
	 * @param unknown_type $req
	 * @return unknown
	 */
	function socket_getdata($host, $path, $req) {
		$header = "POST $path HTTP/1.0\r\n";
		$header .= "Host: " . $host . "\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "User-Agent:      Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0\r\n";
		$header .= "Content-Length: " . strlen ( $req ) . "\r\n\r\n";
		$header .= $req;
		$fp = @fsockopen ( $host, 80, $errno, $errstr, 60 );
		if(!$fp) return ;
		@fwrite ( $fp, $header );
		$data = '';
		$i = 0;
		do {
			$header .= @fread ( $fp, 1 );
		} while ( ! preg_match ( '/\\r\\n\\r\\n$/', $header ) );
		
		while ( ! @feof ( $fp ) ) {
			$data .= @fgets ( $fp, 128 );
		}
		fclose ( $fp );
		return $data;
	}

	function get_Version_Link()
	{
		$link = array();
		$link['1.0.0 Beta']['info'] = "http://wiki.joomlart.com/wiki/JA_Voice/Overview#JA_Voice_1.0";
		$link['1.0.0']['info'] = "http://wiki.joomlart.com/wiki/JA_Voice/Overview#JA_Voice_1.0";			
		return $link;
	}
	
	function get_license_type(){
		global $javconfig;
		
		if ($javconfig['license']->get('type')==md5('professional')){
			return 'Professional';
		}
		elseif ($javconfig['license']->get('type')==md5('standard')){
			return 'Standard';
		}
		else return 'Trial';
	}	
		
	function populateDB ($sqlfile, &$db, &$error) {
		$change_md_sqls = JAVoiceHelpers::splitSql($sqlfile);
		foreach ($change_md_sqls as $query) 
		{
			$query = trim($query);
			if ($query != '') 
			{
				$db->setQuery($query);
				if (!$db->query()) 
				{
					$error[] =" Not run ".$query;
				} 
			}
		}
		return $error;
	}
	
	function splitSql($sqlfile)
	{
		$sql = file_get_contents($sqlfile);
		$sql = trim($sql);
		$sql = preg_replace("/\n\#[^\n]*/", '', "\n".$sql);
		$buffer = array ();
		$ret = array ();
		$in_string = false;
	
		for ($i = 0; $i < strlen($sql) - 1; $i ++) {
			if ($sql[$i] == ";" && !$in_string)
			{
				$ret[] = substr($sql, 0, $i);
				$sql = substr($sql, $i +1);
				$i = 0;
			}
	
			if ($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\")
			{
				$in_string = false;
			}
			elseif (!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset ($buffer[0]) || $buffer[0] != "\\"))
			{
				$in_string = $sql[$i];
			}
			if (isset ($buffer[1]))
			{
				$buffer[0] = $buffer[1];
			}
			$buffer[1] = $sql[$i];
		}
	
		if (!empty ($sql))
		{
			$ret[] = $sql;
		}
		return ($ret);
	}
	function Install_Db(){		
		global $JVVERSION;

		$version_list = array();
		$db = JFactory::getDBO();
		
		$q = "SELECT data FROM #__jav_configs where `group`='others'";
		$db->setQuery( $q );
		$data = $db->loadResult();
		$param = new JParameter($data);
		
		$var = 	'installed_db_config_1_0';//.str_replace('.', '_', $JBVERSION);
		if($param->get($var, 0)==0) 
		{
			$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_javoice'.DS.'installer'.DS.'sql'.DS.'install.configData.sql';
			
			$error = null;
			if(file_exists($path)){
				JAVoiceHelpers::populateDB ($path, $db, $error);
				if($error){
					$error = implode("<br/>", $error);
					return JError::raiseError(1, $error);
				}
				else{			
					
					$param->set($var, 1);
					$data = $param->toString();
					
					$sql = "Update #__jav_configs Set data='$data' Where `group`='others'";
					$db->setQuery($sql);
					$db->query();
				}
			}
			else{
				JError::raiseWarning(1, JText::_('Sql file not found. Installation process default data failed.').'<br /><br />');
				
			}	
		}
		
		if (count($version_list)>0)
		{
			$err_msg = '';
			for ($i=0;$i<count($version_list);$i++)
			{
				$error = null;
				$filename = 'upgrade_v'.$version_list[$i];
				$var = 	'upgrade_'.str_replace('.','_',$version_list[$i]);
				if($param->get($var, 0)) continue;
				$upgrade_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_javoice'.DS.'installer'.DS.'sql'.DS.$filename.'.sql';
				if(file_exists($upgrade_path)){
					JAVoiceHelpers::populateDB ($upgrade_path, $db, $error);
					if($error){
						$error = implode("<br/>", $error);
						return JError::raiseError(1, $error);
					}
					else{			
						
						$param->set($var, 1);
						$data = $param->toString();
						
						$sql = "Update #__jav_configs Set data='$data' Where `group'='others'";
						$db->setQuery($sql);
						$db->query();
					}				
				}
				else{
	//				JError::raiseWarning(1, $filename.".sql ".JText::_('file not found.'));
					$err_msg .= $filename.".sql ".JText::_('file not found.').'<br />';
				}			
			}
			if ($err_msg!='')
			{
				$err_msg .= '<br />'.JText::_('Upgrade data failed.');
				JError::raiseWarning(1, $err_msg);
	//			echo $err_msg;
			}
		}
		
	}
}

?>