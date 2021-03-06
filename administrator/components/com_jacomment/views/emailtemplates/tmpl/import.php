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
?>
<script type="text/javascript">
	function show_hide_file(value){
		if(value=='default'){
			$('userfile').disabled = true;
		}
		else $('userfile').disabled = false;
	}
</script>
<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data">
    
	<fieldset>
		<legend><?php echo JText::_('Import Email Template');?></legend>
		
		<table class="admintable" align="center">		        	
			<!--<tr>
				<td class="key" align="right" style="width:240px">
					<?php echo JText::_( 'Import from' ); ?>:<br/>
				</td>
				
				<td>
					
					<input type="radio" name="source" id="source1" value="file" checked="checked" onclick="show_hide_file(this.value)"/> <label for="source1"><?php echo JText::_('file on your local')?></label>
					<input type="radio" name="source" id="source0" value="default" onclick="show_hide_file(this.value)"/> <label for="source0"><?php echo JText::_('all email template language English')?></label>
				</td>				
			</tr>-->
			<tr>
				<td class="key" align="right" style="width:240px">
					<?php echo JText::_( 'File' ); ?>:<br/>
					<small><?php echo JText::_('only support file types .ini')?></small>
				</td>
				
				<td>
					<input type="file" name="userfile" id="userfile"/>
					
				</td>				
			</tr>
			<tr>
				<td class="key" align="right" style="width:240px">
					<?php echo JText::_( 'Import Language' ); ?>:
				</td>
				
				<td>
					<?php echo $this->languages; ?>
				</td>				
			</tr>
			<tr>
				<td class="key" align="right" >
					<?php echo JText::_('Overwritten if the template already exists?')?>
				</td>
				<td>
					<?php echo JHTML::_('select.booleanlist', 'overwrite', '', 0);?>
				</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td align="left">
					<input type="submit" class="btn_add import" value="<?php echo JText::_('Import')?>" />
					<input type="button" class="btn_add cancel" onclick="window.history.go(-1)" value="<?php echo JText::_('Cancel')?>" />
				</td>
			</tr>
			
		</table>	
	</fieldset>					
					
	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="view" value="emailtemplates" />
	<input type="hidden" name="task" value="import" />
	<?php echo JHTML::_( 'form.token' ); ?>	
 </form>