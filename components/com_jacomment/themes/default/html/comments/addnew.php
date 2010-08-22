<?php // no direct access
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
defined('_JEXEC') or die('Restricted access');
//show login form when only user show comment
$currentUserInfo = JFactory::getUser();
if($currentUserInfo->guest && $postComment != "all"){
?>
	<div id="jac-post-new-comment">
		<a href="javascript:open_login('<?php echo JText::_("Login now");?>');"><?php echo JText::_("Please login to post new comment.");?></a>	
	</div>
<?php	
}
//show add new form
else{
	$display=FALSE;
	JHTML::_('behavior.tooltip'); 
	if(isset($this->preview_enable_youtube)){
		$enableYoutube = $this->preview_enable_youtube;
	}		
	if(isset($this->preview_enable_bbcode)){
		$enableBbcode = $this->preview_enable_bbcode;
	}
	if(isset($this->preview_enable_after_the_deadline)){
		$enableAfterTheDeadline = $this->preview_enable_after_the_deadline;
	}
	if(isset($this->preview_enable_smileys)){
		$enableSmileys = $this->preview_enable_smileys;
	}
	
	$fileTemplate  =  JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS."com_jacomment".DS."themes".DS.$theme.DS."images".DS."loading.gif";
	$linkFile			 = "";
	if(file_exists($fileTemplate)){
		$linkFile =  'templates/'.$mainframe->getTemplate().'/html/com_jacomment/themes/'.$theme.'/images/loading.gif';	
	 }else{		 			 	
		if(file_exists('components/com_jacomment/themes/'.$theme.'/images/loading.gif')){		 			
			$linkFile =  'components/com_jacomment/themes/'.$theme.'/images/loading.gif';	
		}else{
			$linkFile =  'components/com_jacomment/themes/default/images/loading.gif';	
		}
	 }						 
?>
       
<div id="jac-post-new-comment">
	<ul class="form-comment">
		<li class="clearfix form-comment" id="jac-editor-addnew">
			<?php require_once $helper->jaLoadBlock("comments/editor.php");?>			        
		</li>
		<!--BEGIN  Upload form-->
		<?php if($isAttachImage){?>
		<li class="clearfix form-upload" style="display: none;" id="jac-form-upload">
				<?php unset($_SESSION['jaccount']);unset($_SESSION['jactemp']);unset($_SESSION['jacnameFolder']);?>
				<form id="form1" name="form1" enctype="multipart/form-data" method="post" action="index.php">													
				<div id="jac-upload" class="clearfix">
					<p class="ja-error" id="err_myfile"></p>
					<div class="jac-upload-form">						
						<input name="myfile" id="myfile" type="file" size="20" <?php if($totalAttachFile <= 0) echo 'disabled="disabled"';?> onchange="startUpload(1);" class="field file" tabindex="5"/>
						<span id="jac_upload_process" class="jac-upload-loading" style="display: none;">
							<img src="<?php echo $linkFile;?>" alt="<?php echo JText::_("Loading"); ?>"/>
						</span>
						<div class="small"><?php echo JText::_("Attached file");?> (&nbsp;<?php echo JText::_("Total:");?> <?php echo $totalAttachFile; ?> <?php if($totalAttachFile>1){ echo JText::_("files - Max size:").'&nbsp;<b>'.$helper->getSizeUploadFile().'</b>';}else{ echo JText::_("file - Max size:").'&nbsp;<b>'.$helper->getSizeUploadFile().'</b>';}?>&nbsp;)</div>														
					</div>																																								
					<div id="result_upload"></div>					
				</div>	
				</form>	
		</li>
		<?php }?>
		<!--END  Upload form-->		
	</ul>		
		<div class="jac-act-form" style="display: none;"></div>		
		<div id="err_newcomment" style="color: red;"></div>
		<!-- Expan form-->
			<div class="jac-expand-form clearfix">				
				<!--BEGIN TEXT LOGIN OR LOOUT-->			
				<ul>								
				<?php if (!$currentUserInfo->guest) {?>
				<li class="clearfix">				
					<div id="jac-text-user">
						<?php echo JText::_('Posting as')." ".$currentUserInfo->username;?>(<a href="<?php echo JURI::base().'index.php?option=com_jacomment&amp;view=users&amp;task=logout_rpx';?>"><?php echo JText::_("Logout");?></a>)
					</div>		
				</li>            
				<?php }else{?>
				<li class="clearfix">							
				<label class="description" id="title1"><?php echo JText::_("Comment as a Guest or ");?><strong><a href="javascript:open_login();"><?php echo JText::_("Login");?></a></strong></label>																				
				<?php }?>
				<!--END TEXT LOGIN OR LOOUT-->								
				<!--BEGIN  Name, email, website-->
				<?php if($currentUserInfo->guest){?>	
				<div id="other_field" class="clearfix">
				<div class="form-userdata clearfix">
					<!-- BEGIN TEXT NAME-->
					<span class="jac-form-guest">
						<label for="guestName"><?php echo JText::_("Name");?> <span id="required_1" class="required">*</span></label>
						<input id="guestName" name="name" type="text" class="field text inputbox" size="18" tabindex="2" title="<?php echo JText::_("Displayed next to your comments.");?>"/>						
						<div id="err_guestName" style="color: red;"></div>											
					</span>
					<!-- END TEXT NAME-->
					<!-- BEGIN TEXT EMAIL-->
					<span class="jac-form-guest">
						<label for="guestEmail"><?php echo JText::_("Email");?> <span id="required_2" class="required">*</span></label>
						<input id="guestEmail" name="email" type="text" class="field text inputbox" value="" size="18" tabindex="3" title="<?php echo JText::_("Not displayed publicly.");?>"/>
						<div id="err_guestEmail" style="color: red;"></div>											
					</span>
					<!-- END TEXT EMAIL-->
					<!-- BEGIN WEBSITE-->
					<?php if($isEnableWebsiteField){?>
					<span class="jac-form-guest">
						<label for="guestWebsite"><?php echo JText::_("Website");?></label>
						<input id="guestWebsite" name="website" type="text" class="field text inputbox" value="http://" size="18" tabindex="4" title="<?php echo JText::_("(Optional) "); echo JText::_("If you have a website, link to it here.");?>" />												
					</span>
					<?php }?>
					<!-- END WEBSITE-->
				</div></div>
				</li>											
				<?php }?>		
				<!--END  Name, email, website-->
				<?php if($isShowCaptcha){?>																			
				<li class="clearfix">
					<!-- BEGIN -  CAPTCHA -->								
					<div id="jac-new-captcha">
						<img alt="Captcha Image" onmousemove="actionjacLoadNewCaptcha('show')" onmouseout="actionjacLoadNewCaptcha()" onclick="jacLoadNewCaptcha(0)" id="jac_image_captcha"  src="index.php?option=com_jacomment&amp;task=displaycaptchaaddnew"/>						
						<div id="jac-refresh-image" style="display:none;"><img alt="" src="<?php echo $linkFile;?>" /></div>
						<div class="type_captcha"><span><label for="textCaptcha"><?php echo JText::_("Input captcha text here");?><span id="required_4" class="required">&nbsp;*</span></label><input type="text" name="captcha" class="field text inputbox" id="textCaptcha" tabindex="5" value=""/><div id="err_textCaptcha" style="color: red;"></div></span></div>						
					</div>					
					<!-- END -  CAPTCHA -->
				</li>
				<?php }?>
					<!-- BEGIN -  EMAIL SUBSCRIPTION -->							
				<?php if($isEnableEmailSubscription){?>
				<li class="clearfix">
					<div class="jac-subscribe clearfix">
						<span class="jac-text-blow-guest"> <?php echo JText::_("Subscribe to"); ?></span>&nbsp;
						<?php
							$listSubscribe = array();$listSubscribe[0] = JHTML::_('select.option','0',JText::_("None"));$listSubscribe[1] = JHTML::_('select.option','1',JText::_('Replies'));$listSubscribe[2] = JHTML::_('select.option','2',JText::_('New comments'));						
							echo JHTML::_('select.genericlist', $listSubscribe, 'subscription_type','tabindex="6"','value','text', 0);									
						?>
						<br />						
						</div>			
					</li>
					<?php }?>		
					<!-- END -  EMAIL SUBSCRIPTION -->
					<!-- BEGIN -  TERM AND CONDITION -->
					<?php if($isEnableTerms) { ?>	
					<li class="clearfix">
						<div id="jac-terms" style="clear: both" class="checkbox">																	
							<label class="jac-terms-confirm choice" for="chkTermsAddnew">
								<input type="checkbox" name="chkTermsAddnew" id="chkTermsAddnew"  tabindex="7"/>&nbsp;<?php echo JText::_("I have read, and agree to abide by the ");?><a onclick="showWebsiteRules('<?php echo JText::_("Website rules");?>')" href="#" class="jac-link-website-rules" title="<?php echo "Website rules";?>"><?php echo JText::_("Website rules.");?></a>
								<div id="err_TermsAddnew" style="color: red;"></div>		
							</label>																																												
						</div>
					</li>
					<?php } ?>
					<!-- END -  TERM AND CONDITION -->
			</ul>
		</div>					
	<p id="ja-addnew-error" class="ja-error" style="display: none;"></p>
	<ul>
	<li class="buttons clearfix">
	<!--BEGIN - action buttion	-->
	<div class="jac-addnew clearfix" style="clear:both;">
		<input type="button" class="btTxt" tabindex="8" onclick="cancelComment('cancelReply',0,'<?php echo JText::_("Reply");?>','<?php echo JText::_("Posting");?>')" style="display: none;" title="<?php echo JText::_("Cancel");?>" id="btlCancelComment" value="<?php echo JText::_("Cancel");?>"/>	
		<input type="button" class="btTxt" tabindex="9" onclick="postNewComment()" title="<?php echo JText::_("Post new comment");?>" id="btlAddNewComment" value="<?php echo JText::_("Submit Comment");?>"/>			
		<!--<a tabindex="8" href="javascript:cancelComment('cancelReply',0,'<?php echo JText::_("Reply");?>','<?php echo JText::_("Posting");?>')" style="display: none;" title="<?php echo JText::_("Cancel");?>" id="jac_cancel_comment_link"><?php echo JText::_("Cancel");?></a>
		<a tabindex="9" href="javascript:postNewComment()" title="<?php echo JText::_("Post new comment");?>" id="jac_post_new_comment"><?php echo JText::_("Submit Comment");?></a>
		<span id="jac_span_post_new_comment" style="display: none;"><?php echo JText::_("Submit Comment");?></span>-->		
	</div>
	<!--END - action buttion	-->
	</li>
	</ul>
</div>	
<?php }?>