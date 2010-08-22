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
/**
 * This controller is used for JAConfiguration feature of the component
 */
class JACommentControllerConfigs extends JACommentController {
	
	/**
	 * Constructor
	 */
	function __construct($jbconfig = array()) {
		parent::__construct ( $jbconfig );
		$this->registerTask ( 'loadlanguage', 'loadLanguage' );
		$this->registerTask ( 'savelanguage', 'saveLanguage' );
		$this->registerTask ( 'saveblockblack', 'saveBlockBlack' );
		$this->registerTask ( 'removeblockblack', 'removeBlockBlack' );
	
	}
	
	/**
	 * Display current configs of the component to administrator
	 * 
	 */
	function display() {
		parent::display ();
	}
	
	function editcss() {
		JRequest::setVar ( 'edit', true );
		JRequest::setVar ( 'layout', 'editcss' );
		parent::display ();
	}
	/**
	 * Save configuration record
	 */
	function save() {
		global $option;
		$model = & $this->getModel ( 'configs' );
		$item = $model->getItems ();
		
		$data = $item->data;
		$params = new JParameter ( $data );
		
		JRequest::checkToken () or jexit ( 'Invalid Token' );
		$task = $this->getTask ();
		$cache = & JFactory::getCache ( $option );
		$cache->clean ();
		
		$group = JRequest::getVar ( 'group', 'general' );
		if ($group == '')
			$group = 'general';
		$paramsField = JRequest::getVar ( $group, null, 'post', 'array' );
		
		// Build parameter INI string
		$access = JRequest::getVar ( 'access', NULL );
		if (isset ( $access ))
			$paramsField ['access'] = $access;
			
		// ++ add by congtq
		$category = JRequest::getVar ( 'category', NULL );
		if (is_array ( $category ) && ! empty ( $category )) {
			$categories = implode ( ", ", $category );
			$paramsField ['category'] = $categories;
		}
		
		// --
		

		// ++ added by congtq 19/10/2009
		

		// general tab
		if ($group == 'general') {
			if (! $paramsField ['is_comment_offline'])
				$paramsField ['is_comment_offline'] = 0;			
			if (! $paramsField ['is_notify_admin'])
				$paramsField ['is_notify_admin'] = 0;
			if (! $paramsField ['is_notify_author'])
				$paramsField ['is_notify_author'] = 0;
			if (! $paramsField ['is_enabled_email'])
				$paramsField ['is_enabled_email'] = 0;
			if (! $paramsField ['mail_view_only'])
				$paramsField ['mail_view_only'] = 0;
			if (! $paramsField ['is_use_ja_login_form'])
				$paramsField ['is_use_ja_login_form'] = 0;
		}
		// comment tab
		if ($group == 'comments') {
			if (! $paramsField ['is_enable_threads'])
				$paramsField ['is_enable_threads'] = 0;
				//BEGIN - ADD by NghiaTD - change config of vote
			if (@! $paramsField ['is_allow_voting'])
				$paramsField ['is_allow_voting'] = 0;
				//END - ADD by NghiaTD	- change config of vote			
			$paramsField ['attach_file_type'] = implode ( ",", $paramsField ['attach_file_type'] );
			
			if (! $paramsField ['is_attach_image'])
				$paramsField ['is_attach_image'] = 0;			
			if (! $paramsField ['is_enable_website_field'])
				$paramsField ['is_enable_website_field'] = 0;
			if (! $paramsField ['is_enable_autoexpanding'])
				$paramsField ['is_enable_autoexpanding'] = 0;			
			if (! $paramsField ['is_enable_email_subscription'])
				$paramsField ['is_enable_email_subscription'] = 0;
				//if(!$paramsField['is_enable_report']) $paramsField['is_enable_report']=0;
			//BEGIN - ADD by NghiaTD - change config of vote
			if (! $paramsField ['is_allow_report'])
				$paramsField ['is_allow_report'] = 0;
				//END - ADD by NghiaTD	- change config of vote
			if (! $paramsField ['is_allow_approve_new_comment'])
				$paramsField ['is_allow_approve_new_comment'] = 0;
			if (! $paramsField ['is_enable_rss'])
				$paramsField ['is_enable_rss'] = 0;
		}
		// spamfilters tab
		if ($group == 'spamfilters') {
			if (! $paramsField ['is_enable_captcha'])
				$paramsField ['is_enable_captcha'] = 0;
			if (! $paramsField ['is_enable_captcha_user'])
				$paramsField ['is_enable_captcha_user'] = 0;
			if (! $paramsField ['is_use_akismet'])
				$paramsField ['is_use_akismet'] = 0;
			if (! $paramsField ['is_enable_terms'])
				$paramsField ['is_enable_terms'] = 0;
			if (! $paramsField ['is_nofollow'])
				$paramsField ['is_nofollow'] = 0;
		}		
		
		// layout tab
		if ($group == 'layout') {
			if (! $paramsField ['enable_avatar'])
				$paramsField ['enable_avatar'] = 0;
			if (! $paramsField ['use_default_avatar'])
				$paramsField ['use_default_avatar'] = 0;
			if (! $paramsField ['enable_login_button'])
				$paramsField ['enable_login_button'] = 0;
			if (! $paramsField ['enable_subscribe_menu'])
				$paramsField ['enable_subscribe_menu'] = 0;
			if (! $paramsField ['enable_sorting_options'])
				$paramsField ['enable_sorting_options'] = 0;
			if (! $paramsField ['enable_timestamp'])
				$paramsField ['enable_timestamp'] = 0;
			if (! $paramsField ['enable_user_rep_indicator'])
				$paramsField ['enable_user_rep_indicator'] = 0;
			if (! $paramsField ['enable_comment_form'])
				$paramsField ['enable_comment_form'] = 0;
			
			if (! $paramsField ['enable_login_rpx'])
				$paramsField ['enable_login_rpx'] = 0;
			if (! $paramsField ['enable_addthis'])
				$paramsField ['enable_addthis'] = 0;
			if (! $paramsField ['enable_addtoany'])
				$paramsField ['enable_addtoany'] = 0;
			if (! $paramsField ['enable_after_the_deadline'])
				$paramsField ['enable_after_the_deadline'] = 0;
			if (! $paramsField ['enable_polldaddy'])
				$paramsField ['enable_polldaddy'] = 0;
			if (! $paramsField ['enable_seesmic'])
				$paramsField ['enable_seesmic'] = 0;
			if (! $paramsField ['enable_smileys'])
				$paramsField ['enable_smileys'] = 0;
			if (! $paramsField ['enable_tweetmeme'])
				$paramsField ['enable_tweetmeme'] = 0;
			if (! $paramsField ['enable_youtube'])
				$paramsField ['enable_youtube'] = 0;
			if (! $paramsField ['enable_bbcode'])
				$paramsField ['enable_bbcode'] = 0;				
			if (! $paramsField ['enable_activity_stream'])
				$paramsField ['enable_activity_stream'] = 0;
		}
		// -- added by congtq 19/10/2009
		

		if ($paramsField) {
			foreach ( $paramsField as $k => $v ) {
				$params->set ( $k, $v );
			}
			
			$post ['data'] = $params->toString ();
			
			$model->setState ( 'request', $post );
			if ($id = $model->store ()) {
				if ($group == 'layout') {
					$msg = $this->saveCustomCSS ( $paramsField ['custom_css'] );
				}
				
				$msg = JText::_ ( 'UPDATED CONFIGURATION SAVED SUCCESSFULLY' );
			} else {
				$msg = JText::_ ( 'ERROR! CONFIGURATION UPDATE FAILED.' );
			}
		}
		if ($task != 'saveIFrame') {
			$this->setRedirect ( "index.php?option=$option&view=configs&group=$group", $msg );
		} else {
			return true;
		}
		return true;
	}
	
	function loadLanguage() {
		$helper = new JACommentHelpers ( );
		$type = JRequest::getVar ( 'type' );
		$name = JRequest::getVar ( 'name' );
		
		if ($type == 'frontend') {
			$dir = JPATH_SITE . DS . 'components' . DS . 'com_jacomment' . DS . 'languages' . DS;
		} else {
			$dir = JPATH_COMPONENT . DS . 'languages' . DS;
		}
		$content = $helper->loadLangFile ( $dir . $name . '.php' );
		echo $content;
	}
	
	function saveLanguage() {
		$type = JRequest::getVar ( 'type' );
		$name = JRequest::getVar ( 'name' );
		$content = JRequest::getVar ( 'content' );
		
		if ($type == 'frontend') {
			$dir = JPATH_SITE . DS . 'components' . DS . 'com_jacomment' . DS . 'languages' . DS;
		} else {
			$dir = JPATH_COMPONENT . DS . 'languages' . DS;
		}
		
		while ( @ ob_end_clean () )
			;
		$content = "<?php\n" . $content . "?" . ">";
		$content = stripslashes ( $content );
		
		$filename = $dir . $name . '.php';
		
		$handle = fopen ( $filename, "w" );
		fwrite ( $handle, ($content) );
		fclose ( $handle );
		
		echo $content;
	}
	
	function saveBlockBlack() {
		$model = $this->getModel ( 'configs' );
		$tab = JRequest::getVar ( 'tab' );
				
		$arr_str = explode ( "\n",  strtoupper($model->getBlockBlack ()) );
		$data = JRequest::getVar ( 'data' );	
		$data = preg_replace("/[^a-zA-Z0-9-,\\.\\@\s]/", "", $data);		
		$data = preg_replace('/\s*\s/m',' ', $data);
		$data = preg_replace('/\n*\n/m','\n', $data);
		$data = str_replace ( ' ', '\n', $data );
		//auto replace special character.
		
		$data = strtoupper($data);
		
		$arr_data = explode ( '\n', $data );													
		
		$arr_data = array_unique ( $arr_data );
		// ++ check existed word
		
		$arrExit = array ();
		
		$arr_temp = $arr_data;
		for($i = 0; $i < count ( $arr_temp ); $i ++) {
			if (@in_array ( $arr_temp [$i], $arr_str )) {
				$arrExit [] = @$arr_temp [$i];
				unset ( $arr_data [$i] );
			}
		}
		
		$msg = '';
		
		if (isset ( $arrExit ) && (count ( $arrExit ) > 0)) {
			if (count ( $arrExit ) > 1) {
				if ($tab == "blocked_word_list" || $tab == "blacklist_word_list") {
					$msg = "<span id='jac-word-error'>".JText::_ ( 'These words already exist.' )."</span>";
				} else if ($tab == "blocked_ip_list" || $tab == "blacklist_ip_list") {
					$msg = "<span id='jac-ip-error'>".JText::_ ( 'These IP addresses already exist.' )."</span>";
				} else {
					$msg = "<span id='jac-email-error'>".JText::_ ( 'These email addreses already exist.' )."</span>";
				}
			} else {
				if ($tab == "blocked_word_list" || $tab == "blacklist_word_list") {
					if($data == "" || $data == "\n"){
						$msg = "<span id='jac-word-error'>".JText::_ ( 'You must input word.' )."</span>";
					}else{
						$msg = "<span id='jac-word-error'>".JText::_ ( 'This word already exists.' )."</span>";		
					}					
				} else if ($tab == "blocked_ip_list" || $tab == "blacklist_ip_list") {
					if($data == "" || $data == "\n"){						
						$msg = "<span id='jac-ip-error'>".JText::_ ( 'You must input IP.' )."</span>";
					}else{
						$msg = "<span id='jac-ip-error'>".JText::_ ( 'This IP address already exists.' )."</span>";		
					}					
				} else {
					if($data == "" || $data == "\n"){						
						$msg = "<span id='jac-email-error'>".JText::_ ( 'You must input email address.' )."</span>";
					}else{
						$msg = "<span id='jac-email-error'>".JText::_ ( 'This email address already exists.' )."</span>";		
					}										
				}
			}
		}
		
		if (count ( $arr_data ) > 0) {
			$strData = implode ( "\n", $arr_data );
			if (! $model->saveBlockBlack ( $strData )) {
				$msg = "<span id='jac-blocked-error'>".JText::_ ( 'ERROR OCCURRED! DATA NOT SAVED' )."</span>";
			}
		}
		
		$arr_str = explode ( "\n", $model->getBlockBlack () );
		asort ( $arr_str );
		foreach ( $arr_str as $k => $v ) {
			if ($v) {
				$msg .= "<li id='" . $tab . "_" . $k . "' onclick='javascript: remove_blockblack(\"" . $tab . "\", \"" . $k . "\");'>" . $v . "</li>";
			}
		}
		
		echo $msg;
		exit ();
	
	}
	
	function removeBlockBlack() {
		$model = $this->getModel ( 'configs' );
		
		$tab = JRequest::getVar ( 'tab' );
		
		if (! $model->removeBlockBlack ()) {
			$msg = JText::_ ( 'ERROR OCCURRED! DATA NOT SAVED' );
		
		} else {
			$arr_str = explode ( "\n", $model->getBlockBlack () );
			if (sizeof ( $arr_str ) > 1) {
				asort ( $arr_str );
				$msg = '';
				foreach ( $arr_str as $k => $v ) {
					if ($v) {
						$msg .= "<li id='" . $tab . "_" . $k . "' onclick='javascript: remove_blockblack(\"" . $tab . "\", \"" . $k . "\");'>" . $v . "</li>";
					}
				}
			} else {
				$msg = JText::_ ( 'No keyword are currently blacklisted.' );
				if ($tab == "blocked_word_list") {
					$msg = JText::_ ( 'No keyword is currently blocked.' );
				} else if ($tab == "blocked_ip_list") {
					$msg = JText::_ ( 'No ip address is currently blocked.' );
				} else if ($tab == "blocked_email_list") {
					$msg = JText::_ ( 'No email address is currently blocked.' );
				} else if ($tab == "blacklist_word_list") {
					$msg = JText::_ ( 'No keyword is currently blacklisted.' );
				} else if ($tab == "blacklist_ip_list") {
					$msg = JText::_ ( 'No ip address is currently blacklisted.' );
				} else {
					$msg = JText::_ ( 'No email address is currently blacklisted.' );
				}
			}
		}
		
		echo $msg;
		exit ();
	}
	
	function saveAddUser() {
		$result = TRUE;
		$helper = new JACommentHelpers ( );
		$objects = array ();
		
		$model = & $this->getModel ( 'configs' );
		$data = $model->getItems ();
		$item = &JTable::getInstance ( 'configs', 'Table' );
		$item->bind ( $data );
		
		$data = $item->data;
		$params = new JParameter ( $data );
		
		$group = JRequest::getVar ( 'group', NULL );
		if (! $group)
			$result = FALSE;
		
		if ($result) {
			$cid = JRequest::getVar ( 'cid', array (), '', 'array' );
			
			$count = count ( $cid );
			
			$user_id = '';
			$cids = array ();
			
			if ($cid)
				$cids [] = implode ( ',', $cid );
			
			if ($params->get ( 'moderator', '' ) != '')
				$cids [] = $params->get ( 'moderator', '' );
			
			if ($cids)
				$user_id = implode ( ',', $cids );
			
			$params->set ( 'moderator', $user_id );
			$item->group = $group;
			$item->data = $params->toString ();
			
			if ($item->store () && $count > 0) {
				//$objects [] = $helper->parseProperty ( "reload", "#reload" . $item->id, 1 );				
				//$message [] = JText::_ ( "SAVE DATA SUCCESSFULLY" );
				//$objects [] = $helper->parseProperty ( "html", "#system-message", $helper->message ( 0, $message ) );
				

				// ++ add by congtq 18/12/2009
				$modelMod = & JModel::getInstance ( 'moderator', 'JACommentModel' );
				
				$uid = implode ( ",", $cid );
				$where_more = ' AND u.id IN (' . $uid . ')';
				$items = $modelMod->getItems ( $where_more );
				
				$no = substr_count ( $item->data = $params->toString (), ',' );
				
				$content = '';
				for($i = 0; $c = sizeof ( $items ), $i < $c; $i ++) {
					if ($items [$i]->usertype)
						$items [$i]->usertype = $items [$i]->usertype;
					else
						$items [$i]->usertype = JText::_ ( "Registered " );
					
					$content .= '<tr class="row0">
                                    <td align="center">' . ($i + 1 + $no) . '</td>
                                    <td><input id="cb' . ($i + $no) . '" name="cid[]" value="' . $items [$i]->id . '" onclick="isChecked(this.checked);" type="checkbox"></td>
                                    <td>' . $items [$i]->username . '</td>
                                    <td>' . $items [$i]->usertype . '</td>
                                    <td align="left">' . $items [$i]->email . '</td>
                                    <td align="center">' . $items [$i]->id . '</td>
                                </tr>';
				}
				
				$k = 0;
				$objects [$k] = new stdClass ( );
				$objects [$k]->id = '#user_added';
				$objects [$k]->type = 'append_id';
				$objects [$k]->status = 'ok';
				$objects [$k]->content = $content;
				$k ++;
				
				// -- add by congtq 18/12/2009
				

				$helper->displayInform ( JText::_ ( "SAVE DATA SUCCESSFULLY" ), $k, $objects );
			
			} else {
				//$message [] = JText::_ ( "ERROR OCCURRED! DATA NOT SAVED" );
				//$objects [] = $helper->parseProperty ( "html", "#system-message", $helper->message ( 1, $message ) );
				$helper->displayInform ( JText::_ ( "ERROR OCCURRED! PLEASE SEARCH AND SELECT USER" ), $k, $objects );
			}
		} else {
			$message [] = JText::_ ( "ERROR OCCURRED! DATA NOT SAVED" );
			$objects [] = $helper->parseProperty ( "html", "#system-message", $helper->message ( 1, $message ) );
		}
		
		$data = "({'data':[";
		
		$data .= $helper->parse_JSON ( $objects );
		
		$data .= "]})";
		
		echo $data;
		exit ();
	}
	
	function remove() {
		$group = JRequest::getVar ( 'group', NULL );
		$cid = JRequest::getVar ( 'cid', array (0 ), '', 'array' );
		if (! isset ( $group ) || count ( $cid ) == 0) {
			$message = JText::_ ( "ERROR OCCURRED! DATA NOT SAVED" );
			$this->setRedirect ( "index.php?option=com_jacomment&view=configs&group=moderator", $message );
		} else {
			$model = & $this->getModel ( 'configs' );
			$data = $model->getItems ();
			$config = &JTable::getInstance ( 'configs', 'Table' );
			$config->bind ( $data );
			
			$params = new JParameter ( $config->data );
			$user = $params->get ( 'moderator', NULL );
			$user_new = array ();
			
			$error = $this->checkDeletePermission ( $cid );
			
			if ($user) {
				$user = explode ( ",", $user );
				$user_new = array_diff ( $user, $cid );
			} else {
				$model_moderator = & JModel::getInstance ( 'moderator', 'JACommentModel' );
				/*				if ($type== 'admin') {
						$where_more .= " AND u.usertype in ('Manager','Administrator','Super Administrator')";
				}	*/
				$items = $model_moderator->getItems ( '', 1000000, 0, '' );
				if (count ( $items ) > 0) {
					foreach ( $items as $item ) {
						if (! in_array ( $item->id, $cid )) {
							$user_new [] = $item->id;
						}
					}
				}
			}
			if ($user_new)
				$user_new = implode ( ',', $user_new );
			else
				$user_new = '';
			$user_new = 'moderator=' . $user_new;
			$config->data = $params->set ( 'moderator', $user_new );
			$config->group = $group;
			if ($config->store ()) {
				if ($error) {
					foreach ( $error as $err ) {
						JError::raiseWarning ( 1001, $err );
						$this->setRedirect ( "index.php?option=com_jacomment&view=configs&group=moderator" );
					}
				} else {
					$message = JText::_ ( "Delete data successfully." );
					$this->setRedirect ( "index.php?option=com_jacomment&view=configs&group=moderator", $message );
				}
			} else {
				$message = JText::_ ( "Error occurred! Data not saved." );
				$this->setRedirect ( "index.php?option=com_jacomment&view=configs&group=moderator", $message );
			}
		}
		return TRUE;
	}
	function checkDeletePermission(&$cid) {
		$cid_not = array ();
		$error = array ();
		foreach ( $cid as $id ) {
			$currentUser = & JFactory::getUser ();
			$acl = & JFactory::getACL ();
			$objectID = $acl->get_object_id ( 'users', $id, 'ARO' );
			$groups = $acl->get_object_groups ( $objectID, 'ARO' );
			$this_group = strtolower ( $acl->get_group_name ( $groups [0], 'ARO' ) );
			
			if ($this_group == 'super administrator') {
				//You cannot delete a Super Administrator
				$error [] = JText::_ ( "You can not delete a Super Administrator" );
				$cid_not [] = $id;
			} else if ($id == $currentUser->get ( 'id' )) {
				//You cannot delete Yourself!
				$error [] = JText::_ ( "YOU CAN NOT REMOVE YOURSELF" );
				$cid_not [] = $id;
			} else if (($this_group == 'administrator') && ($currentUser->get ( 'gid' ) == 24)) {
				//WARNDELETE
				$cid_not [] = $id;
			} else {
				$user = & JUser::getInstance ( ( int ) $id );
				$count = 2;
				
				if ($user->get ( 'gid' ) == 25) {
					// count number of active super admins
					$query = 'SELECT COUNT( id )' . ' FROM #__users' . ' WHERE gid = 25' . ' AND block = 0';
					$db->setQuery ( $query );
					$count = $db->loadResult ();
				}
				
				if ($count <= 1 && $user->get ( 'gid' ) == 25) {
					// cannot delete Super Admin where it is the only one that exists
					$error [] = JText::_ ( "Can not delete Super Admin where it is the only one that exists" );
					$cid_not [] = $id;
				}
			}
		}
		if ($cid_not)
			$cid = array_diff ( $cid, $cid_not );
		return $error;
	}
	
	function saveEditCSS() {
		jimport ( 'joomla.filesystem.file' );
		
		$post = JRequest::get ( 'request' );
		
		$theme = $post ['theme'];
		$content = $post ['content'];
		
		$themeFolders = JPATH_SITE . '/components/com_jacomment/themes/';
		$file = $themeFolders . $theme . '/css/style.css';
		
		$helper = new JACommentHelpers ( );
		$objects = array ();
		$k = 0;
		if (JFile::write ( $file, $content )) {
			//$message [] = JText::_ ( "SAVE DATA SUCCESSFULLY" );
			//$objects [] = $helper->parseProperty ( "html", "#system-message", $helper->message ( 0, $message ) );
			

			$k = 0;
			$objects [$k] = new stdClass ( );
			$objects [$k]->id = '#system-message';
			$objects [$k]->type = 'html';
			$objects [$k]->status = 'ok';
			$objects [$k]->content = '';
			$k ++;
			
			$helper->displayInform ( JText::_ ( "SAVE DATA SUCCESSFULLY" ), $k, $objects );
		
		} else {
			//$message [] = JText::_ ( "ERROR OCCURRED! DATA NOT SAVED" );
			//$objects [] = $helper->parseProperty ( "html", "#system-message", $helper->message ( 1, $message ) );          
			$helper->displayInform ( JText::_ ( "ERROR OCCURRED! DATA NOT SAVED" ), $k, $objects );
		}
		$data = "({'data':[";
		
		$data .= $helper->parse_JSON ( $objects );
		
		$data .= "]})";
		
		echo $data;
		exit ();
	}
	
	function saveCustomCSS($content) {
		jimport ( 'joomla.filesystem.file' );
		
		$helper = new JACommentHelpers ( );
		$template = $helper->getTemplate ();
		
		$file = JPATH_SITE . '\templates\\' . $template . '\css\ja.comment.custom.css';
		if(JFile::exists($file)){
			if (JFile::write ( $file, $content )) {
				$message = JText::_ ( "SAVE CSS SUCCESSFULLY" );
			} else {
				$message = JText::_ ( "ERROR OCCURRED! CSS NOT SAVED" );
			}
		}else{
			$message = JText::_ ( "Can't find this file." );
		}				
		return $message;
	}
}
?>