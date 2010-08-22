<?php  
defined('_JEXEC') or die('Retricted Access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.switcher');
$selected = 'selected="selected"';
?>
<script type="text/javascript" language="javascript">
	var actionNumberCharacter = "min";
//	function submitbutton(pressbutton){
//		var form = document.adminForm;
//		var checkInteger  = /(^\d\d*$)/;						
//		
//		numberMax  = $("max_length").value;
//		numberMin  = $("min_length").value; 
//		if(!checkInteger(numberMax) || !checkInteger(numberMin)){
//			//error_min_length
//			if(!checkInteger(numberMax)){
//				$("error_max_length").innerHTML = $("hdInvalidMax").value;
//				alert($("hdInvalidMax").value);
//				$("max_length").focus();						
//			}else{
//				$("error_min_length").innerHTML = $("hdInvalidMin").value;
//				alert($("hdInvalidMin").value);
//				$("min_length").focus();								
//			}		
//			return;		
//		}else{						
//			if( parseInt(numberMin,10) >= parseInt(numberMax,10)){					
//				if(actionNumberCharacter == "min"){				
//					$("error_min_length").innerHTML = $("hdInvalidCharacter").value;
//					alert($("hdInvalidCharacter").value);
//					$("min_length").focus();					
//				}else{				
//					$("error_max_length").innerHTML = $("hdInvalidCharacter").value;
//					alert($("hdInvalidCharacter").value);
//					$("max_length").focus();		
//				}													
//				return;
//			}				
//		}
//
//		if(!checkInteger($("number_of_links").value)){		
//			$("error_number_of_links").innerHTML = $("hdInvalidMaxLink").value;
//			$("number_of_links").focus();
//			alert($("hdInvalidMaxLink").value);
//			return;
//		}
//				
//	    form.task.value = pressbutton;
//	    form.submit();		
//	}
</script>

<script type="text/javascript">
//jQuery.noConflict();

jQuery(document).ready(function(){
	jQuery.each( ["word","ip","email"], function(i, n){
		jQuery("#add_blocked_" + n + "_link").click(function () {
			jQuery("#add_blocked_" + n + "").show("fast");
			jQuery("#add_blocked_" + n + "_link").hide("");
            jQuery("#ta_blocked_" + n + "_list").focus();
		});
		jQuery("#blocked_" + n + "_cancel").click(function () {
			jQuery("#add_blocked_" + n + "_link").show("");
			jQuery("#add_blocked_" + n + "").hide("");			
			if(jQuery("#jac-" + n + "-error").length >0){
				jQuery("#jac-" + n + "-error").attr('style', 'display:none');	
			}			
		});
	});
    
    jQuery.each( ["enable_terms"], function(i, n){
        jQuery("#is_" + n).click(function () {
            if(jQuery("#is_" + n).is(':checked')){
                jQuery("#ja-block-" + n).show("");    
            }else{
                jQuery("#ja-block-" + n).hide("");    
            }
        });
    });
    
    
    jQuery.each( ["enable_captcha","enable_terms"], function(i, n){
        jQuery("input[id='is_"+n+"']").click(function() {
            show_bar_preview('<?php echo JText::_('Preview')?>', '<?php echo JText::_('Cancel')?>');
        });  
    });     
    
    

});

function save_blockblack(tab){ 
    data = document.getElementById("ta_"+tab).value;  
    if(data){
        jQuery.ajax({
            type: "POST",
            url: "index.php?tmpl=component&option=com_jacomment&view=configs&group=spamfilters&task=saveblockblack",
            data: "&data=" + data + "&tab=" + tab,
            success: function(html){
                jQuery("#"+tab).html(html);
                document.getElementById("ta_"+tab).value='';
                jQuery("#ta_"+tab).focus();
            }
        });
    }    
}
function remove_blockblack(tab, id){  
    jQuery.ajax({
        type: "POST",
        url: "index.php?tmpl=component&option=com_jacomment&view=configs&group=spamfilters&task=removeblockblack",
        data: "tab=" + tab + "&id=" + id,
        success: function(html){
            jQuery("#"+tab).html(html);
        }
    });    
}    

jQuery(function() {
    jQuery(document).ajaxSend(function() {
        jQuery('#indicator').html('<?php echo JHTML::_('image', JURI::root().'administrator/components/com_jacomment/asset/images/loading.gif', '', '');?>');    
    });
    jQuery(document).ajaxStop(function() {
        jQuery('#indicator').html('');    
    });
});    
</script>
<form action="index.php" method="post" name="adminForm">
<div class="col100">
	<fieldset class="adminform TopFieldset">
		<?php echo $this->getTabs();?>
	</fieldset>
	<br/>
	
	<div id="SpamFiter">
		<div class="box">
			<h2><?php echo JText::_('Spam filter Settings');?></h2>	
			<div class="box_content">
				<ul class="ja-list-checkboxs">
					<li class="row-1 ja-section-title">
						<h4><?php echo JText::_('Captcha Settings')?></h4>
					</li>
					<li class="row-0">				
						<label for="is_enable_captcha">
							<?php $isEnableCaptcha = $this->params->get('is_enable_captcha', 0);?>
							<input type="checkbox" onclick="checkValidCaptcha(this);" <?php if($isEnableCaptcha){?>checked="checked"<?php }?> value="1" name="spamfilters[is_enable_captcha]" id="is_enable_captcha"/> 
							<?php echo JText::_('Enable captcha image security')?>
						</label>
						<p class="info"><?php echo JText::_('Enable captcha-image for guest. Poster needs to type in the displayed character in order to post a new comment.')?></p>
					</li>
					
					<li class="row-0">
						<label for="is_enable_captcha_user">
							<?php $isEnableCaptchaUser = $this->params->get('is_enable_captcha_user', 0);?>
							<input type="checkbox" <?php if($isEnableCaptchaUser){?>checked="checked"<?php }?> onclick="checkValidCaptcha(this);" value="1" name="spamfilters[is_enable_captcha_user]" id="is_enable_captcha_user"/> 
							<?php echo JText::_("Enable captcha for registered user");?>
						</label>
						<p class="info"><?php echo JText::_('Enable captcha-image for registered user. Poster needs to type in the displayed character in order to post a new comment.')?></p>
					</li>
					
					<!--<li class="row-0 ja-section-title">
						<b><?php echo JText::_('Akismet Spam Detection')?></b>
					</li>			
					<li class="row-1">
						<label for="is_use_akismet">
							<?php $isUseAkismet = $this->params->get('is_use_akismet', 0);?>
							<input type="checkbox" <?php if($isEnableCaptcha){?>checked="checked"<?php }?> value="1" name="spamfilters[is_use_akismet]" id="is_use_akismet" onclick="isuseakismet($('is_use_akismet'))"/> 
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Use Akismet spam detection service' );?>::<?php echo JText::_( 'Use Akismet spam detection service TOOLTIP' ); ?>">
								<?php echo JText::_("Use Akismet spam detection service");?>
							</span>
						</label>
						<div class="child clearfix" id='div_display_akismet'>
							<div class="editlinktip hasTip" title="<?php echo JText::_( 'Akismet access key' );?>::<?php echo JText::_( 'Akismet access key TOOLTIP' );?>">
								<?php echo JText::_("Akismet access key");?>
							</div>					
							<input type="text" name="spamfilters[akismet_key]" value="<?php echo $this->params->get('akismet_key');?>" id="akismet_key" size="40"/>
							<br/>
							<small><?php echo JText::_('(To retrieve your Akismet API Key you must create a new Wordpress.com account or have an existing one. Your API Key is sent via email after you activate your account)')?></small>
						</div>	
					</li>-->
					
					<li class="row-1 ja-section-title" style="margin-top: 13px;">
						<b><?php echo JText::_('Terms & Conditions')?></b>
					</li>			
					<li class="row-0">				
						<label for="is_enable_terms">
							<?php $isEnableTerms = $this->params->get('is_enable_terms', 0);?>
							<input type="checkbox" <?php if($isEnableTerms){?>checked="checked"<?php }?> value="1" name="spamfilters[is_enable_terms]" id="is_enable_terms" onclick="isenableterms($('is_enable_terms'))"/> 
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Enable terms and conditions' );?>::<?php echo JText::_('Enable the terms and conditions at comment page.')?>">
								<?php echo JText::_("Enable terms and conditions");?>
							</span>
						</label>
						<div id="ja-block-enable_terms"<?php if(!$isEnableTerms){?>style="display:none"<?php } ?>>
							<div class="child clearfix" id='div_display_terms'>
								<div class="editlinktip hasTip" title="<?php echo JText::_( 'Terms of usage' );?>::<?php echo JText::_( 'Terms of usage TOOLTIP' );?>">
									<?php echo JText::_("Terms of usage");?>:
								</div>					
								<textarea name="spamfilters[terms_of_usage]" id="terms_of_usage" cols="50" rows="5"><?php echo $this->params->get('terms_of_usage');?></textarea>
							</div>
						</div>					
					</li>
					
					<li class="row-1 ja-section-title" style="margin-top: 13px;">
						<b><?php echo JText::_('Block Settings')?></b>
					</li>
					
					<li class="row-0 pd10">
						<div class="tab_list">
							<ul id="ja-tabs">
								<li><a id="words" class="active"><?php echo JText::_('Words')?></a></li>
								<li><a id="ips"><?php echo JText::_('IP Addresses')?></a></li>
								<li><a id="emails"><?php echo JText::_('Email Addresses')?></a></li>
							</ul>				
							<span id="indicator"></span>
							<div class="clr"></div>
							<div id="ja-tabs-content">
								<div id="page-words">
									<p><?php echo JText::_('The comments containing these added words here will be automatically deleted.');?></p>
									<ul id="blocked_word_list"><?php echo $this->lists['blocked_word_list'];?></ul>
									<div id="add_blocked_word" style="display: none; width: 400px;">
										<textarea id="ta_blocked_word_list" name="blocked_word_list" style="width: 400px;text-transform:uppercase;"></textarea><p>
											<span style="float: right; display: inline;">
											<a href="javascript: void('');" id="blocked_word_cancel" class="btn_add cancel"><?php echo JText::_('Cancel');?></a>
											<input value="<?php echo JText::_('Save');?>" class="button btn_add" type="button" onclick="javascript:save_blockblack('blocked_word_list');"></span>
											<span><?php echo JText::_('Add multiple Words separated by a space.');?></span>
										</p>
									</div>
									<p style="display: block;" id="add_blocked_word_link">
										<a href="javascript: void('');" class="btn_add"><?php echo JText::_('Add Word(s)');?></a>
									</p>
								</div>
								<div id="page-ips">
									<p><?php echo JText::_('The comments coming from added IP addresses here will be automatically deleted.');?></p>
									<ul id="blocked_ip_list"><?php echo $this->lists['blocked_ip_list'];?></ul>
									<div id="add_blocked_ip" style="display: none; width: 400px;">
										<textarea id="ta_blocked_ip_list" class="text" style="width: 400px;"></textarea>
										<p>
											<span style="float: right; display: inline;">
											<a href="javascript: void('');" id="blocked_ip_cancel" class="btn_add cancel"><?php echo JText::_('Cancel');?></a>
											<input value="<?php echo JText::_('Save');?>" class="button btn_add" type="button" onclick="javascript:save_blockblack('blocked_ip_list');"></span>
											<span><?php echo JText::_('Add multiple IPs separated by a space.');?></span>
										</p>
									</div>
									<p style="display: block;" id="add_blocked_ip_link">
										<a href="javascript: void('');" class="btn_add"><?php echo JText::_('Add IP address(es)');?></a>
									</p>
								</div>
								<div id="page-emails">
									<p><?php echo JText::_('The comments coming from added email addresses here will be automatically deleted.');?></p>
									<ul id="blocked_email_list"><?php echo $this->lists['blocked_email_list'];?></ul>
									<div id="add_blocked_email" style="display: none; width: 400px;">
										<textarea id="ta_blocked_email_list" class="text" style="width: 400px;"></textarea>
										<p>
											<span style="float: right; display: inline;">
											<a href="javascript: void('');" id="blocked_email_cancel" class="btn_add cancel"><?php echo JText::_('Cancel');?></a>
											<input value="<?php echo JText::_('Save');?>" class="button btn_add" type="button" onclick="javascript:save_blockblack('blocked_email_list');"></span>
											<span><?php echo JText::_('Add multiple Emails separated by a space.');?></span>
										</p>
									</div>
									<p style="display: block;" id="add_blocked_email_link">
										<a href="javascript: void('');" class="btn_add"><?php echo JText::_('Add Email address(es)');?></a>
									</p>
								</div>
							</div>
							<div class="clr"></div>
						</div>
					</li>
					
					<li class="row-1 ja-section-title" style="margin-top: 13px;">
						<b><?php echo JText::_('Other Spam Settings')?></b>
					</li>
					<li class="row-0">				
						<label for="min_length">
							<a href='#' name="#min_length"></a>
							<input type="text" onkeypress="return isNumberKey(event, this)" onkeyup="checkValidKey(this.value,'min_length')" maxlength="4" name="spamfilters[min_length]" value="<?php echo $this->params->get('min_length', 10);?>" id="min_length" size="3" onfocus="checkNumberCharacter('min', this)" /> 
							<?php echo JText::_("character (s) is minimum required for a Comment");?>
						</label>
						<p style="color: red;" id="error_min_length"></p>
						<p><?php echo JText::_( 'The minimum number of character(s) a user must post.' ); ?></p>
						<input type="hidden" id="hidden_min_length" value="<?php echo $this->params->get('min_length', 10);?>" />
						<label for="max_length">					
							<input type="text" onkeypress="return isNumberKey(event, this)" onkeyup="checkValidKey(this.value,'max_length')" maxlength="4" name="spamfilters[max_length]" value="<?php echo $this->params->get('max_length', 300);?>" id="max_length" size="3" onchange="checkNumberCharacter('max', this)"/> 
							<?php echo JText::_("character (s) is the maximum allowed for a Comment");?>
						</label>
						<p style="color: red;" id="error_max_length"></p>
						<p><?php echo JText::_('The maximum number of character(s) a user can post.')?></p>

						<label for="censored_words">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Censored words' );?>::<?php echo JText::_( "All censored words will appear as **** . For example, 'censored' will appear as c******d.. Seperated by comma ," ); ?>">
								<?php echo JText::_("Censored words");?>
							</span>
						</label>
						<div class="child clearfix">								
							<textarea name="spamfilters[censored_words]" id="censored_words" cols="50" rows="5"><?php echo $this->params->get('censored_words');?></textarea>
						</div>	

						<label for="censored_words_replace">					
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Word for replacement' );?>::<?php echo JText::_( 'Word for replacement TOOLTIP' ); ?>">
								<?php echo JText::_("Word for replacement");?>
							</span>
						</label>
						<div class="child clearfix">								
							<input type="text" name="spamfilters[censored_words_replace]" maxlength="<?php if($this->params->get('max_length', 300)>100){echo "100";}else{echo $this->params->get('max_length', 300);}?>" value="<?php echo $this->params->get('censored_words_replace');?>" id="censored_words_replace" size="70"/>
							<input type="hidden" id="hidden_censored_words_replace" value="<?php echo $this->params->get('censored_words_replace');?>"/>
						</div>	

						<label for="is_nofollow">	
							<?php $isNofollow = $this->params->get('is_nofollow', 1);?>
							<input type="checkbox" <?php if($isNofollow){?>checked="checked"<?php }?> value="1" name="spamfilters[is_nofollow]" id="is_nofollow"/>				
							<?php echo JText::_("Add 'rel=nofollow' on outgoing links");?>
						</label>
						<p><?php echo JText::_("By adding Add 'rel=nofollow' to outgoing links, search engine will ignore the link and will not crawl the link")?></p>

						<label for="number_of_links">
							<input type="text" onkeypress="return isNumberKey(event, this)" onkeyup="checkValidKey(this.value,'number_of_links')" maxlength="4" name="spamfilters[number_of_links]" value="<?php echo $this->params->get('number_of_links',5);?>" id="number_of_links" size="3" onchange="checkMaxLink()"/> 
							<?php echo JText::_("link(s) is the maximum allowed per comment.");?>
						</label>
						<p style="color: red;" id="error_number_of_links"></p>
						<p><?php echo JText::_('The maximum number of link (s) a user can post in a comment.')?></p>
					</li>
				</ul>
			</div>
		</div>
	</div>			
</div>
<input type="hidden" id="hdInvalidCharacter" value="<?php echo JText::_("invalid inputing number of characters in comment. The maximum number is always greater than minimum.");?>" />
<input type="hidden" id="hdInvalidMin" value="<?php echo JText::_("Minimum must be number, not null and greater than 0.");?>" />
<input type="hidden" id="hdInvalidMax" value="<?php echo JText::_("Maximum must be number, not null and greater than 0.");?>" />
<input type="hidden" id="hdInvalidMaxLink" value="<?php echo JText::_("Maximum of link must be number, not null and greater than 0.");?>" />
<input type="hidden" id="hdCurrentInputCharacter" value="min_length" />
<input type="hidden" name="option" value="com_jacomment" />
<input type="hidden" name="view" value="configs" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="group" value="<?php echo $this->group; ?>" />
<input type="hidden" name="cid" value="<?php echo $this->cid; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>	
</form>
<script>
//function isuseakismet(obj){
//	if(!obj.checked) $('akismet_key').disabled = true;
//	else $('akismet_key').disabled = false;
//	
//}
//isuseakismet($('is_use_akismet'));

function checkValidCaptcha(obj){
	if(obj.id == "is_enable_captcha_user"){
		if(obj.checked == true){
			$("is_enable_captcha").checked = true;
		}
	}else{
		if(obj.checked == false){
			$("is_enable_captcha_user").checked = false;
		}
	}	
}

function checkValidKey(value,obj){	
	if(value == 0){
		$(obj).value = "";
	}
}

function isNumberKey(evt, obj){
	   var charCode = (evt.which) ? evt.which : evt.keyCode
	   if (charCode > 31 && (charCode < 48 || charCode > 57))
	      return false;	   	
	   return true;
}

function checkMaxLink(){
	var checkInteger  = /(^\d\d*$)/;
	if(!checkInteger($("number_of_links").value)){		
		$("error_number_of_links").innerHTML = $("hdInvalidMaxLink").value;
	}else{
		$("error_number_of_links").innerHTML = "";
	}
}

function checkNumberCharacter(action, obj){		
	var checkInteger  = /(^\d\d*$)/;
	numberMax  = $("max_length").value;
	numberMin  = $("min_length").value; 

	if(action == "max"){
		if(numberMax>100){
			$("censored_words_replace").maxLength = 100;
		}else{
			$("censored_words_replace").maxLength = numberMax;
		}
		$("censored_words_replace").value = $("hidden_censored_words_replace").value.substring(0, numberMax);  
	}
	
	if(!checkInteger(numberMax) || !checkInteger(numberMin)){
		//error_min_length
		if(!checkInteger(numberMax)){
			$("error_max_length").innerHTML = $("hdInvalidMax").value;						
		}
		if(!checkInteger(numberMin)){
			$("error_min_length").innerHTML = $("hdInvalidMin").value;						
		}		
		return;		
	}else{								
		if( parseInt(numberMin,10) >= parseInt(numberMax,10)){						
			if(action == "min"){				
				//$("error_min_length").innerHTML = $("hdInvalidCharacter").value;
				$("max_length").value = parseInt(numberMin,10) + 1;		
			}else{				
				//$("error_max_length").innerHTML = $("hdInvalidCharacter").value;
				if(numberMax <= 0){
					$("error_max_length").innerHTML = $("hdInvalidMax").value;
					$("max_length").value = 1;
					$("min_length").value = 0;		
				}else{
					$("min_length").value = numberMax - 1;
				}
				actionNumberCharacter = "max";
			}													
			return;
		}		
	}
			
	$("error_max_length").innerHTML = "";
	$("error_min_length").innerHTML = "";
}

function isenableterms(obj){
	if(!obj.checked) $('terms_of_usage').disabled = true;
	else $('terms_of_usage').disabled = false;	
}
isenableterms($('is_enable_terms'));

window.addEvent('domready', function(){
 	toggler = $('ja-tabs');
  	element = $('ja-tabs-content');
  	if(element) {
  		document.switcher = new JSwitcher(toggler, element, {cookieName: toggler.getAttribute('class')});
  	}
});
</script>