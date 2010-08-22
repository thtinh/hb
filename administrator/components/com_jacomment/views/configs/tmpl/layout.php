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
 
defined('_JEXEC') or die('Retricted Access');

JHTML::_('behavior.tooltip');
JHTML::_('behavior.switcher'); 

$selected = 'selected="selected"';
$checked = 'checked="checked"';

// Read the themes folder to find themes
jimport('joomla.filesystem.folder');   

$themeFolders = JPATH_SITE.'/components/com_jacomment/themes/';
$themes = JFolder::folders($themeFolders);

$smileyFolders = JPATH_SITE.'/components/com_jacomment/asset/images/smileys/';
$smileys = JFolder::folders($smileyFolders);

// ++  custom_css

jimport('joomla.filesystem.file');

$helper = new JACommentHelpers ( );
$template = $helper->getTemplate(0);

$file = JPATH_SITE.'\templates\\'.$template.'\css\ja.comment.custom.css';

$custom_css = '';
if(JFile::exists($file)){
    $custom_css = JFile::read($file);
} 

JHTML::_('script', 'jquery.event.drag-1.5.min.js',JURI::root().'administrator/components/com_jacomment/asset/js/');   
?> 

<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery.each( ["avatar","addthis","addtoany","polldaddy","smileys","tweetmeme","comment_form","sorting_options"], function(i, n){        
        jQuery("#enable_" + n).click(function () {            
            if(jQuery("#enable_" + n).is(':checked')){
                jQuery("#ja-block-" + n).show("");    
            }else{
                jQuery("#ja-block-" + n).hide("");    
            }
        });
    });
    //
    <?php foreach($themes as $theme){ ?>
        jQuery("#<?php echo $theme;?>").click(function () { 
            jQuery("#edit_<?php echo $theme;?>").show("");    
            jQuery(".theme").not("#edit_<?php echo $theme;?>").hide("");    
        });
    <?php } ?>
    //
    jQuery("input").click(function() {
        show_bar_preview('<?php echo JText::_('Preview')?>', '<?php echo JText::_('Cancel')?>');
    });
});

function edit_theme(theme){
    jaCreatForm("editcss&group=layout&theme="+theme,0,700,460,0,0,'<?php echo JText::_("Custom CSS: ");?>'+theme,0,'<?php echo JText::_('Save');?>');
}  
 
</script>
<form action="index.php" method="post" name="adminForm">

<div class="col100">
	<fieldset class="adminform TopFieldset">
        <div class="submenu-box">
            <div class="submenu-pad">
                <ul id="submenu" class="configuration">
                    <li><a id="layout" class="active"><?php echo JText::_( 'Layout' ); ?></a></li>
                    <li><a id="plugins"><?php echo JText::_( 'Plugins' ); ?></a></li>
                </ul>
                <div class="clr"></div>
            </div>
        </div>
        <div class="clr"></div>
    </fieldset>
    <br/>
    <div id="config-document">
        <div id="page-layout">
	        <div class="box">
		        <h2><?php echo JText::_( 'Layout Settings' ); ?></h2>	
		        <ul class="ja-list-checkboxs">
			        <li class="row-1 ja-section-title">
				        <b><?php echo JText::_('Choose Theme')?></b>
			        </li>
			        <li class="row-0">
                        <?php                         
                        foreach($themes as $theme){
                            $display = 'style="display:none;"';
                            if($this->params->get('theme', 1)==$theme){
                                $display = 'style="visibility:visible"';
                            }                            
                        ?>
				        <input type="radio" value="<?php echo $theme;?>" name="layout[theme]" <?php if($this->params->get('theme', 1)==$theme) echo $checked;?> id="<?php echo $theme;?>"/>
				        <label for="<?php echo $theme;?>" class="normal" style="font-weight: normal;"><?php echo ucfirst(JText::_($theme))?></label>
                        <span class="theme" id="edit_<?php echo $theme;?>" <?php echo $display;?>> (<a href="javascript:edit_theme('<?php echo $theme;?>');">custom css</a>)</span>
                        <?php } ?>				
			        </li>			        
			        <li class="row-1 ja-section-title">
				        <b><?php echo JText::_('User Avatar')?></b>
			        </li>			        
			        <li class="row-0">
				        <label for="enable_avatar">
					        <?php $EnableAvatar = $this->params->get('enable_avatar', 1);?>
					        <input type="checkbox" <?php if($EnableAvatar==1){ echo $checked; }?> value="1" name="layout[enable_avatar]" id="enable_avatar"/> 
					        <?php echo JText::_("Enable Avatar");?>
				        </label>
				        <br />
				        <div id="ja-block-avatar"<?php if(!$EnableAvatar){?>style="display:none"<?php } ?>>
				        	<?php				        		
				        		 $type_avatar = $this->params->get('type_avatar', 0);				        		 
				        	?>					       
					        <ul>					        	
					        	<select name="layout[type_avatar]" id="type_avatar" multiple="multiple" size="4">						        		
					        		<option value="0" <?php if($type_avatar == 0) echo 'selected="selected"';?> id="type_avatar_0"><?php echo JText::_('Default');?></option>
					        		<option value="1" <?php if($type_avatar == 1) echo 'selected="selected"';?> id="type_avatar_1"><?php echo JText::_('Community Builder');?></option>					        		
					        		<option value="2" <?php if($type_avatar == 2) echo 'selected="selected"';?> id="type_avatar_3"><?php echo JText::_('Fireboard');?></option>
					        		<option value="4" <?php if($type_avatar == 4) echo 'selected="selected"'; ?>><?php echo JText::_('JomSocial');?></option>	
					        		<option value="3" <?php if($type_avatar == 3) echo 'selected="selected"';?> id="type_avatar_4"><?php echo JText::_('Gravatar');?></option>
					        	</select>
					        	<br/>				        								        							        							        								       							      
					        </ul>
					        <!--<br>					       				      
					        <ul>		
					        	<label>
					        		<?php echo JText::_("Path to SMF forum (if required)");?>
					        	</label>			        						        	
					        	<input type="text" style="width: 180px;" name="layout[path_to_smf_forum]" value="<?php echo $this->params->get('path_to_smf_forum', '');?>">					        	
				        		<small><?php echo JText::_('Full path to your SMF forum folder.')?></small>						        							        							        								       							        
					        </ul>-->
					        <br />						       		       
					        <?php $avatar_size = $this->params->get('avatar_size', 1);?>					        	
						        <ul class="ja-list-avatars clearfix">
							        <li <?php if($avatar_size==1){?>class="active"<?php }?> id="ja-li-avatar-1">							        	
								        <label for="avatar_size_1" class="normal">
									        <img width="16" height="16" src="components/com_jacomment/asset/images/settings/layout/avatar-large.png"/>
									        <span>
										        <input onclick="update_avatar_size_selection(1)" <?php if($avatar_size==1) echo $checked?> type="radio" value="1" id="avatar_size_1" name="layout[avatar_size]"/> 
										        <?php echo JText::_('Compact')?>
									        </span>
								        </label>
								        
							        </li>
							        <li <?php if($avatar_size==2){?>class="active"<?php }?> id="ja-li-avatar-2">
								        <label for="avatar_size_2" class="normal">
									        <img width="24" height="24" src="components/com_jacomment/asset/images/settings/layout/avatar-large.png" style="margin-top: 14px;"/>
									        <span>
										        <input onclick="update_avatar_size_selection(2)" <?php if($avatar_size==2) echo $checked?> type="radio" value="2" id="avatar_size_2" name="layout[avatar_size]"/> 
										        <?php echo JText::_('Normal')?>
									        </span>
								        </label>
								        
							        </li>
							        <li <?php if($avatar_size==3){?>class="active"<?php }?> id="ja-li-avatar-3">
								        <label for="avatar_size_3" class="normal">
									        <img src="components/com_jacomment/asset/images/settings/layout/avatar-large.png" style="margin-top: 6px;"/>
									        <span>
										        <input onclick="update_avatar_size_selection(3)" <?php if($avatar_size==3) echo $checked?> type="radio" value="3" id="avatar_size_3" name="layout[avatar_size]"/> 
										        <?php echo JText::_('Large')?>
									        </span>
								        </label>								
							        </li>							        							      
						        </ul>
						         <small><?php echo JText::_('Select which avatar to display.')?></small>
				        </div>				
			        </li>			        			        			        			        
			        
			        <li class="row-1 ja-section-title">
				        <b><?php echo JText::_('Comment Form Position')?></b>
			        </li>
			        <li class="row-0">			        					        
			        	<?php $form_position = $this->params->get('form_position', 1);?>
				        <label for="form_position_1" class="normal">
					        <input type="radio" id="form_position_1" <?php if($form_position==1) echo $checked;?> value="1" name="layout[form_position]"/>
					        <?php echo JText::_('Top of thread')?>
				        </label>
				        <label for="form_position_2" class="normal">
					        <input id="form_position_2" type="radio" <?php if($form_position==2) echo $checked;?> value="2" name="layout[form_position]"/>
					        <?php echo JText::_('Bottom of thread')?> 
				        </label>
				        <p><?php echo JText::_('The position of the form to add a new comment.')?></p>				        				       
			        </li>
			        
			        <li class="row-1 ja-section-title">
				        <b><?php echo JText::_('Appearance')?></b>
			        </li>						
			        <!--<li class="row-1">
				         <?php $enable_login_button = $this->params->get('enable_login_button', 1);?>
				        <label for="enable_login_button">
					        <img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/layout/layout-screenshot-login.png"/>
					        <input type="checkbox" <?php if($enable_login_button) echo $checked;?> value="1" name="layout[enable_login_button]" id="enable_login_button" /> 
					        <?php echo JText::_('Enable IntenseDebate Login button')?>
				        </label>
				        <p class="info"><?php echo JText::_('Enable/Disable the button in the top right of the comment system.')?></p>							
			        </li>
			        <li class="row-0">
				         <?php $enable_subscribe_menu = $this->params->get('enable_subscribe_menu', 1);?>
				        <label for="enable_subscribe_menu">
					        <img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/layout/layout-screenshot-subscribemenu.png"/>
					        <input type="checkbox" <?php if($enable_subscribe_menu) echo $checked;?> value="1" name="layout[enable_subscribe_menu]" id="enable_subscribe_menu" /> 
					        <?php echo JText::_('Subscribe menu')?>
				        </label>
				        <p class="info"><?php echo JText::_('Allow users to subscribe to new comments using popular notification services.')?></p>							
			        </li>-->
			        <li class="row-0">
						<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
							<col width="7%" /><col width="93%" />
							<tr>
								<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/layout/layout-screenshot-sorting.png"/></td>
								<td>
									<?php $enable_sorting_options = $this->params->get('enable_sorting_options', 1);?>
									<label for="enable_sorting_options">
										<input type="checkbox" <?php if($enable_sorting_options) echo $checked;?> value="1" name="layout[enable_sorting_options]" id="enable_sorting_options" /> 
										<?php echo JText::_('Sorting options')?>
									</label>
									<p class="info"><?php echo JText::_('Hide or show the comment sorting options on the top of the comment section.')?></p>
									<div id="ja-block-sorting_options"<?php if(!$enable_sorting_options){?>style="display:none"<?php } ?>>										
										<ul class="list-horizontalselect">
											<?php $default_sort = $this->params->get('default_sort', 'date');?> 
											<li class="fade"><?php echo JText::_("Default sorting:");?></li>
											<li><label for="default_sort_1"><input type="radio" <?php if($default_sort=='date') echo $checked;?> value="date" name="layout[default_sort]" id="default_sort_1"/> </label><?php echo JText::_("Date");?></li>
											<li><label for="default_sort_2"><input type="radio" <?php if($default_sort=='voted') echo $checked;?> value="voted" name="layout[default_sort]" id="default_sort_2"/></label>						        	
											 <?php echo JText::_("Rating");?></li>						        						        
										</ul>
										<br />
										<br />
										<ul class=" clearfix">
											<li class="fade"><?php echo JText::_("Default type sorting:");?></li>
											<li>
												<select name="layout[default_sort_type]">
													<option id="default_sort_type_ASC" <?php if($this->params->get('default_sort_type', 'ASC') == "ASC") echo 'selected="selected"';?>  value="ASC"><?php echo JText::_("ASC");?></option>
													<option id="default_sort_type_DESC" <?php if($this->params->get('default_sort_type', 'ASC') == "DESC") echo 'selected="selected"';?>  value="DESC"><?php echo JText::_("DESC");?></option>
												</select>						        
											</li>
										</ul>
									</div>							
								</td>
							</tr>
							<tr>
								<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/layout/layout-screenshot-timestamp.png"/></td>
								<td>
									<?php $enable_timestamp = $this->params->get('enable_timestamp', 1);?>
									<label for="enable_timestamp">
										<input type="checkbox" <?php if($enable_timestamp) echo $checked;?> value="1" name="layout[enable_timestamp]" id="enable_timestamp" /> 
										<?php echo JText::_('Enable timestamps')?>
									</label>
									<p class="info"><?php echo JText::_('Hide or show the time stamp for comments and the "Last active" indicator for nested comments.')?></p>							
								</td>
							</tr>
						</table>
					</li>
			        <!--<li class="row-1">
				         <?php $enable_user_rep_indicator = $this->params->get('enable_user_rep_indicator', 1);?>
				        <label for="enable_user_rep_indicator">
					        <img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/layout/layout-screenshot-reputation.png"/>
					        <input type="checkbox" <?php if($enable_user_rep_indicator) echo $checked;?> value="1" name="layout[enable_user_rep_indicator]" id="enable_user_rep_indicator" /> 
					        <?php echo JText::_('Enable user reputation indicator')?>
				        </label>
				        <p class="info"><?php echo JText::_("A visual indicator of a commenter's credibility.")?></p>
                    </li>-->
			        
			        <li class="row-1 ja-section-title">
				        <b><?php echo JText::_('Footer text')?></b>
			        </li>
			        <li class="row-0">				
				        <?php echo JText::_('The below text will be shown at the bottom of the comment system.')?>
				        <div class="child">
					        <textarea id="footer_text" class="textarea_border" name="layout[footer_text]" cols="60" rows="3"><?php echo $this->params->get('footer_text');?></textarea>
				        </div>						
			        </li>
                    <li class="row-1 ja-section-title">
                        <b><?php echo JText::_('Custom css')?></b>
                    </li>
                    <li class="row-0">                
                        <?php echo JText::_('Enter your custom style rules here. Click here for CSS help. To use an externally hosted stylesheet, you may type: @import url(http://path-to-css);')?>
                        <div class="child">
                            <textarea id="custom_css" class="textarea_border" name="layout[custom_css]" cols="80" rows="7"><?php echo $this->params->get('custom_css');?></textarea>
                        </div>                        
                    </li>
		        </ul>		
				        
	        </div>
        </div>
        <div id="page-plugins">
	        <div class="box">
		        <h2><?php echo JText::_( 'Login Services' ); ?></h2>
				<div class="box_content">
					<ul class="ja-list-checkboxs">
						<li class="row-1 last_row">
							<?php $enable_login_rpx = $this->params->get('enable_login_rpx', 1);?>
							<label for="enable_login_rpx">
								<img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/rpx.png" height="30"/>
								<input type="checkbox" <?php if($enable_login_rpx) echo $checked;?> value="1" name="layout[enable_login_rpx]" id="enable_login_rpx" /> <?php echo JText::_('Integrate JA RPXnow Plugin')?>
							</label>
							<p class="info"><?php echo JText::_('Enable RPX for Login')?></p>            
						</li>
					</ul>
				</div>
            </div>
			<br />
	        <div class="box">
		        <h2><?php echo JText::_( 'Plugin Settings' ); ?></h2>	
				<div class="box_content">
					<ul class="ja-list-checkboxs">
						<li class="row-0">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/addthis.gif"/></td>
									<td>
										<?php $enable_addthis = $this->params->get('enable_addthis', 1);?>
										<label for="enable_addthis">
											<input type="checkbox" <?php if($enable_addthis) echo $checked;?> value="1" name="layout[enable_addthis]" id="enable_addthis" /> <?php echo JText::_('AddThis')?>
										</label>
										<p class="info"><?php echo JText::_('The #1 Bookmarking & Sharing Service')?></p>            
										<div class="ja-block-inline child" id="ja-block-addthis"<?php if(!$enable_addthis){?>style="display:none"<?php } ?>>
											<textarea id="custom_addthis" class="text" name="layout[custom_addthis]" cols="80" rows="5"><?php echo $this->params->get('custom_addthis');?></textarea>
										</div>
									</td>
								</tr>
							</table>                   
						</li>
						<li class="row-1">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/AddToAny.jpeg"/></td>
									<td>
										<?php $enable_addtoany = $this->params->get('enable_addtoany', 0);?>
										<label for="enable_addtoany">
											<input type="checkbox" <?php if($enable_addtoany) echo 'checked="checked"';?>value="1" name="layout[enable_addtoany]" id="enable_addtoany" /> <?php echo JText::_('AddToAny Share Button')?> 
										</label>
										<p class="info"><?php echo JText::_('Helps readers share, save, bookmark, and email posts using any service, such as Delicious, Digg, Facebook, Twitter, and over 100 more social bookmarking and sharing sites.')?></p>
										<div class="ja-block-inline child" id="ja-block-addtoany"<?php if(!$enable_addtoany){?>style="display:none"<?php } ?>>
											<textarea id="custom_addtoany" class="text" name="layout[custom_addtoany]" cols="80" rows="5"><?php echo $this->params->get('custom_addtoany');?></textarea>
										</div>
									</td>
								</tr>
							</table>                   
						</li>            
						<li class="row-0">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/atdbuttontr.gif"/></td>
									<td>
										<?php $enable_after_the_deadline = $this->params->get('enable_after_the_deadline', 0);?>
										<label for="enable_after_the_deadline">
											<input type="checkbox" <?php if($enable_after_the_deadline) echo 'checked="checked"';?>value="1" name="layout[enable_after_the_deadline]" id="enable_after_the_deadline" /> <?php echo JText::_('After the Deadline - Spell check for Comments')?> 
										</label>
										<p class="info"><?php echo JText::_('Let users check spelling and grammar before submitting their comments. ')?></p>
									</td>
								</tr>
							</table>                   
						</li>						
						<!--<li class="row-0">
							<?php $enable_polldaddy = $this->params->get('enable_polldaddy', 0);?>
							<label for="enable_polldaddy">
								<img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/polldaddy.png"/>
								<input type="checkbox" <?php if($enable_polldaddy) echo 'checked="checked"';?>value="1" name="layout[enable_polldaddy]" id="enable_polldaddy"/> <?php echo JText::_('PollDaddy Embeddable Polls')?>
							</label>
							<p class="info"><?php echo JText::_('Create and add PollDaddy polls to your comment stream and let your readers take the debate to a new dimension! Find out what your visitors are thinking today. Create your surveys and let your readers create polls too!')?></p>
							<div class="ja-block-inline child" id="ja-block-polldaddy"<?php if(!$enable_polldaddy){?>style="display:none"<?php } ?>>
								<textarea id="custom_polldaddy" class="text" name="layout[custom_polldaddy]" cols="80" rows="5"><?php echo $this->params->get('custom_polldaddy');?></textarea>
							</div>
						</li>
						<li class="row-1">
							<?php $enable_seesmic = $this->params->get('enable_seesmic', 0);?>
							<label for="enable_seesmic">
								<img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/seesmic.png"/>
								<input type="checkbox" <?php if($enable_seesmic) echo 'checked="checked"';?>value="1" name="layout[enable_seesmic]" id="enable_seesmic"/> <?php echo JText::_('Seesmic Video Comments')?>
							</label>
							<p class="info"><?php echo JText::_('Activate Seesmic Video Comments in your IntenseDebate comments section. Let your commenters` voices be heard in a whole new way! About Seesmic: "Seesmic provides anyone with an innovative way to communicate and connect online through video conversation."')?></p>
						</li>-->
						<li class="row-1">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/simplysmileys.png"/></td>
									<td>
										<?php $enable_smileys = $this->params->get('enable_smileys', 0);?>
										<label for="enable_smileys">
											<input type="checkbox" <?php if($enable_smileys) echo 'checked="checked"';?>value="1" name="layout[enable_smileys]" id="enable_smileys" /> <?php echo JText::_('Smileys')?>
										</label>
										<p class="info"><?php echo JText::_('Add smileys to your comment section to let your commenters express their digital facial expressions. Sarcasm isn\'t sarcasm without a wink ;-)')?></p>
										<div class="ja-block-inline child" id="ja-block-smileys"<?php if(!$enable_smileys){?>style="display:none"<?php } ?>>
											<ul>
												<li>                                
												<?php                         
												echo JText::_('Select a style:');												
												foreach($smileys as $smiley){
												?>
												<input type="radio" value="<?php echo $smiley;?>" name="layout[smiley]" <?php if($this->params->get('smiley', "default")==$smiley) echo $checked;?> id="smileys<?php echo $smiley;?>"/>
												<label for="smileys<?php echo $smiley;?>" class="normal" style="font-weight: normal;"><?php echo ucfirst(JText::_($smiley))?></label><img src="../components/com_jacomment/asset/images/smileys/<?php echo $smiley;?>/smileys_icon.png" />
												<?php } ?>
												</li>
											</ul>
										</div>
									</td>
								</tr>
							</table>                   
						</li>
						<li class="row-0">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/tm.ico"/></td>
									<td>
										<?php $enable_tweetmeme = $this->params->get('enable_tweetmeme', 0);?>
										<label for="enable_tweetmeme">
											<input type="checkbox" <?php if($enable_tweetmeme) echo 'checked="checked"';?>value="1" name="layout[enable_tweetmeme]" id="enable_tweetmeme"/> <?php echo JText::_('TweetMeme Retweet Button')?>
										</label>
										<p class="info"><?php echo JText::_('Adds the TweetMeme Retweet Button at the top of the comment section.')?></p>
										<div class="ja-block-inline child" id="ja-block-tweetmeme"<?php if(!$enable_tweetmeme){?>style="display:none"<?php } ?>>
											<textarea id="custom_tweetmeme" class="text" name="layout[custom_tweetmeme]" cols="80" rows="5"><?php echo $this->params->get('custom_tweetmeme');?></textarea>
										</div>
									</td>
								</tr>
							</table>                   
						</li>
						<li class="row-1">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/youtube.png"/></td>
									<td>
										<?php $enable_youtube = $this->params->get('enable_youtube', 0);?>
										<label for="enable_youtube">
											<input type="checkbox" <?php if($enable_youtube) echo 'checked="checked"';?>value="1" name="layout[enable_youtube]" id="enable_youtube" /> <?php echo JText::_('YouTube Embeddable Video')?>
										</label>
										<p class="info"><?php echo JText::_('Activate YouTube embeds and your readers will be able to share their favorite YouTube videos and beef up their responses right in the comment section.')?></p>
									</td>
								</tr>
							</table>                   
						</li>
						<li class="row-0">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td><img class="screenshot" alt="Screenshot" src="components/com_jacomment/asset/images/settings/comments/bbcode.png"/></td>
									<td>
										<?php $enable_bbcode = $this->params->get('enable_bbcode', 1);?>
										<label for="enable_bbcode">
											<input type="checkbox" <?php if($enable_bbcode) echo 'checked="checked"';?>value="1" name="layout[enable_bbcode]" id="enable_bbcode" /> <?php echo JText::_('Enable BBcode')?>
										</label>										
									</td>
								</tr>
							</table>                   
						</li>  
						<li class="row-1">
							<table width="100%" cellpadding="0" cellspacing="0" class="tbl tbl_appearance">
								<col width="7%" /><col width="93%" />
								<tr>
									<td></td>
									<td>
										<?php $enable_activity_stream = $this->params->get('enable_activity_stream', 0);?>										
										<label for="enable_activity_stream">
											<input type="checkbox" <?php if(!JACommentHelpers::checkComponent('com_community')){ ?>disabled="disabled"<?php }?> <?php if($enable_activity_stream) echo $checked;?>value="1" name="layout[enable_activity_stream]" id="enable_activity_stream" /> <?php echo JText::_('JomSocial - Activity Stream')?>
										</label>										
										<p class="info">
											<?php echo JText::_('New comment will show up in Activity Stream')?>
											<br/>
											<?php if(!JACommentHelpers::checkComponent('com_community')){ echo JText::_("You must install JomSocial to use it."); }?>
										</p>
										
									</td>
								</tr>
							</table>                   
						</li>           
					</ul>                
				</div>
			</div>
        </div>
    </div>		
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_jacomment" />
<input type="hidden" name="view" value="configs" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="group" value="<?php echo $this->group; ?>" />
<input type="hidden" name="cid" value="<?php echo $this->cid; ?>" />
<?php echo JHTML::_( 'form.token' ); ?> 

<script type="text/javascript">
function update_avatar_size_selection(size){
	if($('ja-li-avatar-' + size)!='undifined'){
		for(var i=1; i<=3; i++){
			$('ja-li-avatar-' + i).removeClass('active');
		}
		$('ja-li-avatar-' + size).addClass('active');
	}
}
</script>
</form>