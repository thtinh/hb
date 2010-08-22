<?php
defined('_JEXEC') or die('Retricted Access');
global $mainframe;
JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');

$selected = 'selected="selected"';
?>
<form action="index.php" method="post" name="adminForm">
<div class="col100">
	<fieldset class="adminform TopFieldset">
		<?php echo $this->getTabs();?>
	</fieldset>
	<br />
    <div id="GeneralSettings">
		<div class="box">
			<h2><?php echo JText::_( 'Permissions' ); ?></h2>	
			<div class="box_content">
				<ul class="ja-list-checkboxs">
					<li>
						<label>	
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'View comments' );?>::<?php echo JText::_( 'Select who can view comments.' ); ?>">
							<?php echo JText::_("View comments");?>
							</span>
							<select name="permissions[view]" id="view" onchange="changeViewComment(this.value)">
							<option value="all"<?php if($this->params->get('view')=='all') echo $selected;?>><?php echo JText::_(" All");?></option>
							<option value="member"<?php if($this->params->get('view')=='member') echo $selected;?>><?php echo JText::_("Only Member");?></option>
							</select>
						</label>
					</li>
					<li>
						<label>    
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Post comments' );?>::<?php echo JText::_( 'Select who can post comments.' ); ?>">
							<?php echo JText::_("Post comments");?>
							</span>
							<select name="permissions[post]" id="post" onchange="changeStatusComment(this)">
								<option value="all"<?php if($this->params->get('post')=='all') echo $selected;?>><?php echo JText::_(" All");?></option>
								<option value="member"<?php if($this->params->get('post')=='member') echo $selected;?>><?php echo JText::_("Only Member");?></option>
							</select>
							<p style="color: red;display: none;" id="error_post"><?php echo JText::_("Post comment applies only to member because view comment is only member.");?></p>
						</label>
					</li>
					<li>
						<label>    
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Vote comments' );?>::<?php echo JText::_( 'Select who can vote on comments.' ); ?>">
							<?php echo JText::_("Vote comments");?>
							</span>
							<select name="permissions[vote]" id="vote" onchange="changeStatusComment(this)">
								<option value="all"<?php if($this->params->get('vote')=='all') echo $selected;?>><?php echo JText::_(" All");?></option>
								<option value="member"<?php if($this->params->get('vote')=='member') echo $selected;?>><?php echo JText::_("Only Member");?></option>
							</select>
							<p style="color: red;display: none;" id="error_vote"><?php echo JText::_("Vote comment applies only to member because view comment is only member.");?></p>
						</label>
						<div style="padding-left:30px;">
							<?php $typeVoting = $this->params->get('type_voting', 1);?>
							<ul>
								<li>
									<label class="child"><input type="radio" id="type_voting_1" name="permissions[type_voting]" value="1" <?php if($typeVoting=="1") echo 'checked="checked"';?> /><?php echo JText::_("Only once for each comment item.");?></label>
								</li>
								<li>
									<label class="child"><input type="radio" id="type_voting_2" name="permissions[type_voting]" value="2" <?php if($typeVoting=="2") echo 'checked="checked"';?> /><?php echo JText::_("Only once for each comment item for each session.");?></label>
								</li>
								<li>
									<label class="child"><input type="radio" id="type_voting_3" name="permissions[type_voting]" value="3" <?php if($typeVoting=="3") echo 'checked="checked"';?> />
									<?php echo JText::_("Lag");?>
									<input onkeypress="return isNumberKey(event)" onkeyup="checkValidKey(this.value,'lag_voting')" maxlength="20" type="text" value="<?php echo $this->params->get('lag_voting', '');?>" id="lag_voting" name="permissions[lag_voting]" <?php if($typeVoting!="3") echo 'disabled="disabled"';?> />(<?php echo JText::_("seconds between votes.");?>)</label>      
								</li>
							</ul>
						</div>
					</li>
					<li>
						<label>    
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Report comments' );?>::<?php echo JText::_( 'Select who can report comments as spam.' ); ?>">
							<?php echo JText::_("Report comments");?>
							</span>
							<select name="permissions[report]" id="report" onchange="changeStatusComment(this)">
								<option value="all"<?php if($this->params->get('report')=='all') echo $selected;?>><?php echo JText::_(" All");?></option>
								<option value="member"<?php if($this->params->get('report')=='member') echo $selected;?>><?php echo JText::_("Only Member");?></option>
							</select>
							<p style="color: red;display: none;" id="error_report"><?php echo JText::_("Report comment applies only to member because view comment is only member.");?></p>
						</label>
						<br style="clear:both;" />
						<div>
							<label for="total_to_report_spam" class="child">
								<?php echo JText::_("Total number of Reports to confirm comment as Spam.");?>      
								<?php $totalToReportSpam = $this->params->get('total_to_report_spam', 0);?>
								<input type="text" onkeyup="checkValidKey(this.value,'total_to_report_spam')" value="<?php echo $this->params->get('total_to_report_spam', '10');?>" id="total_to_report_spam" name="permissions[total_to_report_spam]">                                                    
							</label>
						</div>
					</li>
					<li>
						<label>    
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit comments' );?>::<?php echo JText::_( 'Select time user can edit comments.' ); ?>">
							<?php echo JText::_("Edit comments (Only member)");?>
							</span>							
						</label>
						<br style="clear:both;" />
						<div style="padding-left:30px;">
							<?php $typeEditing = $this->params->get('type_editing', 1);?>
							<ul>
								<li>
									<label class="child"><input type="radio" id="type_editing_1" name="permissions[type_editing]" value="1" <?php if($typeEditing=="1") echo 'checked="checked"';?> /><?php echo JText::_("Always edit comment.");?></label>
								</li>
								<li>
									<label class="child"><input type="radio" id="type_editing_2" name="permissions[type_editing]" value="2" <?php if($typeEditing=="2") echo 'checked="checked"';?> /><?php echo JText::_("Only once for each comment item in an unique section.");?></label>
								</li>
								<li>
									<label class="child"><input type="radio" id="type_editing_3" name="permissions[type_editing]" value="3" <?php if($typeEditing=="3") echo 'checked="checked"';?> />
									<?php echo JText::_("Lag");?>
									<input onkeypress="return isNumberKey(event)" onkeyup="checkValidKey(this.value,'lag_editing')" type="text" maxlength="20" value="<?php echo $this->params->get('lag_editing', '172800');?>" id="lag_editing" name="permissions[lag_editing]" <?php if($typeEditing!="3") echo 'disabled="disabled"';?> />(<?php echo JText::_("seconds after new post, not allow the user to edit comment.");?>)</label>      
								</li>
							</ul>
						</div>
					</li>
				</ul>					
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
</form>
<script> 

function changeViewComment(value){
	if(value == "member"){
		if($("post").selectedIndex == 0)
			$("post").selectedIndex = 1;
		if($("vote").selectedIndex == 0)
			$("vote").selectedIndex = 1;
		if($("report").selectedIndex == 0)
			$("report").selectedIndex = 1;
	}
}

function checkValidKey(value,obj){		
	if(value == 0){
		$(obj).value = "";
	}
}

function changeStatusComment(obj){
	if($("view").value == "member"){		
		if(obj.value == "all"){			
			obj.selectedIndex = 1;
			$("error_" + obj.id).style.display = "block";
		}	
	}
}

function isNumberKey(evt){
   var charCode = (evt.which) ? evt.which : evt.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;

   return true;
}


jQuery(document).ready(function(){
    jQuery.each( ["1","2"], function(i, n){
        jQuery("#type_voting_" + n).click(function () {
            jQuery("#lag_voting").attr('disabled', 'disabled'); ;
        });
    });
    jQuery("#type_voting_3").click(function () {
        jQuery("#lag_voting").removeAttr('disabled');
        jQuery("#lag_voting").focus();
    });
    
    jQuery.each( ["1","2"], function(i, n){
        jQuery("#type_editing_" + n).click(function () {
            jQuery("#lag_editing").attr('disabled', 'disabled'); ;
        });
    });
    jQuery("#type_editing_3").click(function () {
        jQuery("#lag_editing").removeAttr('disabled');
        jQuery("#lag_editing").focus();
    });
});

</script> 