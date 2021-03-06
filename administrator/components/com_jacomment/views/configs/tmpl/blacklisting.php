<?php  
defined('_JEXEC') or die('Retricted Access');

JHTML::_('behavior.tooltip');
JHTML::_('behavior.switcher');

$selected = 'selected="selected"';

?>
<style type="text/css">
#blacklist_word_list, #blacklist_ip_list, #blacklist_email_list{
margin: 0 -2px .5em;
max-height: 180px;
overflow: hidden;
overflow-y: auto;
width: 100%;
}
#blacklist_word_list li, #blacklist_ip_list li, #blacklist_email_list li{
cursor:pointer;    
background: url(<?php echo JURI::root().'administrator/components/com_jacomment/asset/images/remove.png'; ?>) no-repeat;
float: left;
height: 14px;
line-height: 14px;
overflow: hidden;
padding: 0 6px 0 16px !important;
-moz-border-radius: 6px; /* For Firefox */
-khtml-border-radius: 6px; /* For Konqueror */
-webkit-border-radius: 6px; /* For Safari */
border-radius: 6px;
}
#blacklist_word_list li:hover, #blacklist_ip_list li:hover, #blacklist_email_list li:hover{
background-color: #0B55C4;
color: #fff !important;
text-decoration: none;
}


</style>
<script type="text/javascript">
//jQuery.noConflict();

jQuery(document).ready(function(){
	jQuery.each( ["word","ip","email"], function(i, n){
		jQuery("#add_blacklist_" + n + "_link").click(function () {
			jQuery("#add_blacklist_" + n + "").show("fast");
			jQuery("#add_blacklist_" + n + "_link").hide("");
            jQuery("#ta_blacklist_" + n + "_list").focus();
		});
		jQuery("#blacklist_" + n + "_cancel").click(function () {
			jQuery("#add_blacklist_" + n + "_link").show("");
			jQuery("#add_blacklist_" + n + "").hide("");
			if(jQuery("#jac-" + n + "-error").length >0){
				jQuery("#jac-" + n + "-error").attr('style', 'display:none');	
			}
		});
	});

});

function save_blockblack(tab){ 
    data = document.getElementById("ta_"+tab).value;  
    if(data){
        jQuery.ajax({
            type: "POST",
            url: "index.php?tmpl=component&option=com_jacomment&view=configs&group=blacklisting&task=saveblockblack",
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
        url: "index.php?tmpl=component&option=com_jacomment&view=configs&group=blacklisting&task=removeblockblack",
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
	<br />
    <div id="GeneralSettings">
		<div class="box">
			<h2><?php echo JText::_( 'Blacklist Settings' ); ?></h2>	
			<div class="box_content">
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
							<p><?php echo JText::_('The comments containing added words here will be automatically marked as spam.');?></p>
							<ul id="blacklist_word_list"><?php echo $this->lists['blacklist_word_list'];?></ul>
							<div id="add_blacklist_word" style="display: none; width: 400px;">
								<textarea id="ta_blacklist_word_list" name="blacklist_word_list" style="width: 400px;text-transform:uppercase;"></textarea><p>
									<span style="float: right; display: inline;">
									<a href="javascript: void('');" id="blacklist_word_cancel" class="btn_add cancel"><?php echo JText::_('Cancel');?></a>
									<input value="<?php echo JText::_('Save');?>" class="button btn_add" type="button" onclick="javascript:save_blockblack('blacklist_word_list');"></span>
									<span><?php echo JText::_('Add multiple Words separated by a space.');?></span>
								</p>
							</div>
							<p style="display: block;" id="add_blacklist_word_link">
								<a href="javascript: void('');" class="btn_add"><?php echo JText::_('Add Word(s)');?></a>
							</p>
						</div>
						<div id="page-ips">
							<p><?php echo JText::_('The comments coming from added IP addresses here will be automatically marked as spam.');?></p>
							<ul id="blacklist_ip_list"><?php echo $this->lists['blacklist_ip_list'];?></ul>
							<div id="add_blacklist_ip" style="display: none; width: 400px;">
								<textarea id="ta_blacklist_ip_list" class="text" style="width: 400px;"></textarea>
								<p>
									<span style="float: right; display: inline;">
									<a href="javascript: void('');" id="blacklist_ip_cancel" class="btn_add cancel"><?php echo JText::_('Cancel');?></a>
									<input value="<?php echo JText::_('Save');?>" class="button btn_add" type="button" onclick="javascript:save_blockblack('blacklist_ip_list');"></span>
									<span><?php echo JText::_('Add multiple IPs separated by a space.');?></span>
								</p>
							</div>
							<p style="display: block;" id="add_blacklist_ip_link">
								<a href="javascript: void('');" class="btn_add"><?php echo JText::_('Add IP address(es)');?></a>
							</p>
						</div>
						<div id="page-emails">
							<p><?php echo JText::_('The comments coming from added email addresses here will be automatically marked as spam.');?></p>
							<ul id="blacklist_email_list"><?php echo $this->lists['blacklist_email_list'];?></ul>
							<div id="add_blacklist_email" style="display: none; width: 400px;">
								<textarea id="ta_blacklist_email_list" class="text" style="width: 400px;"></textarea>
								<p>
									<span style="float: right; display: inline;">
									<a href="javascript: void('');" id="blacklist_email_cancel" class="btn_add cancel"><?php echo JText::_('Cancel');?></a>
									<input value="<?php echo JText::_('Save');?>" class="button btn_add" type="button" onclick="javascript:save_blockblack('blacklist_email_list');"></span>
									<span><?php echo JText::_('Add multiple Emails separated by a space.');?></span>
								</p>
							</div>
							<p style="display: block;" id="add_blacklist_email_link">
								<a href="javascript: void('');" class="btn_add"><?php echo JText::_('Add Email address(es)');?></a>
							</p>
						</div>
					</div>
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
</form>
<script>
window.addEvent('domready', function(){
    toggler = $('ja-tabs');
    element = $('ja-tabs-content');
    if(element) {
        document.switcher = new JSwitcher(toggler, element, {cookieName: toggler.getAttribute('class')});
    }
});
</script>