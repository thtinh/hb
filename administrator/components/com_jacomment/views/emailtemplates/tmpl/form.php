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
  $editor = &JFactory::getEditor();
  $item = $this->item;

?>

<script type="text/javascript" language="javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;

	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	if ( pressbutton == 'save' || pressbutton == 'apply' ){
		
		if(form.name.value == '' ){
		    alert('<?php echo JText::_('Template name could not be empty')?>');
			form.name.focus();
			return;
		}	
		
		if(form.title.value == '' ){
		    alert('<?php echo JText::_('Title could not be empty')?>');
			form.name.focus();
			return;
		}	
		

		if(form.subject.value == '' ){
		    alert('<?php echo JText::_('Subject could not be empty')?>');
			form.subject.focus();
			return;
		}		
		
		submitform( pressbutton );
	}					
	else {
		submitform( pressbutton );
	}
}
function insertVariable(el){
    if (!el.selectedIndex) return;
    txt = el.form.elements.content;   
    content =  el.options[ el.selectedIndex ].value;
    if(tinyMCE.majorVersion=='undefine'){
    	tinyMCE.execCommand('mceInsertContent',false,content);
    }else{
        content = tinyMCE.getContent()+content;
        tinyMCE.setContent(content);     
    }
}
function formReload(frm, reload){
    frm.elements.reload.value = reload;
    frm.submit();
}

//jQuery(document).ready(
//		function($) {
//			tinyMCE.dom.Event.add(document, 'click', function(e) {
//				   console.debug(e.target);
//				});

//		});
</script>
<form name="adminForm" action="index.php" method="post">

<fieldset>
	<legend><?php if($item->id) echo JText::_('Edit Email Template'); else echo JText::_('Add Email Template');?></legend>
	<table class="admintable">		        	
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'Template name' ); ?>:
			</td>
			
			<td width="50%" colspan="2">
				<input class="inputbox" type="text" name="name" size="50" maxlength="255" value="<?php echo $item->name; ?>" <?php if($item->name) echo "disabled";?> /> 
				<font color="Red">*</font>
			</td>				
       	</tr>
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'Title' ); ?>:
			</td>
			
			<td width="50%" colspan="2">
				<input class="inputbox" type="text" name="title" size="50" maxlength="255" value="<?php echo $item->title; ?>" /> 
				<font color="Red">*</font>
			</td>				
       	</tr>
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'Language' ); ?>:
			</td>
			<td width="50%" colspan="2">
			
				<?php echo $this->languages; ?>
			</td>
       	</tr> 
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'Email Template Group' ); ?>:
			</td>
			<td width="50%" colspan="2">
					<?php echo $this->group; ?>
			</td>
       	</tr> 
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'From Address' ); ?>:
			</td>
			<td width="50%" colspan="2">
				<input class="inputbox" type="text" name="email_from_address" size="50" maxlength="255" value="<?php echo $item->email_from_address; ?>" />
				<?php echo JText::_("(Leave blank to use setting from System Global setting )")?>
			</td>
       	</tr>   
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'From name' ); ?>:
			</td>
			<td width="50%" colspan="2">
				<input class="inputbox" type="text" name="email_from_name" size="50" maxlength="255" value="<?php echo $item->email_from_name; ?>" />
				<?php echo JText::_("(Leave blank to use setting from System Global setting )")?>
			</td>
       	</tr>   
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'Subject' ); ?>:
			</td>
			<td width="50%" colspan="2">
				<input class="inputbox" type="text" name="subject" size="50" maxlength="255" value="<?php echo $item->subject; ?>" />
				<font color="Red">*</font>
			</td>
       	</tr>   
       	       	
       	
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td width="50%" colspan="2">
				<?php echo $this->published?>
			</td>
       	</tr>
       	
       	<tr>
       		<td class="key" width="20%" align="right">
				<?php echo JText::_( 'Content' ); ?>:										
			</td>
			<td width="50%" colspan="2">							
				<?php echo $editor->display('content', $item->content, '550','300','70', '50',false,array('theme'=>'simple'));?>
			</td>
			<td width="30%" valign="top">
 				<table class="adminlist" width="100%">
 					<thead>
	 					<tr>
	 						<th class="key">
	 							<?php echo JText::_( 'E-Mail Variables' ); ?>:
	 						</th>
	 					</tr>
 					</thead>
 					<tbody>
	 					<tr>
	 						<td>
	 							<?php echo $this->tags?>
	 						</td>
	 					</tr>
 					</tbody>
 				</table>			
			</td>
       	</tr>
   	</table> 			
</fieldset>    

<?php if($item->id){?>
<input type="hidden" name="name" value="<?php echo $item->name; ?>"/>
<?php }?>
<input type="hidden" name="id" value="<?php echo $item->id; ?>" />
<input type="hidden" name="cid" value="<?php echo $item->id; ?>" />
<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="view" value="emailtemplates" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>	
<!--<input type="button" onclick="tinyMCEOnDemand();return false;" value="OK">-->
</form>