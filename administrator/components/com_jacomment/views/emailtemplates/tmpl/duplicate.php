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

<form name="adminForm" action="index.php" method="post">
    
	<fieldset>
		<legend><?php echo JText::_('Duplicate Email Template');?></legend>
		
		<table class="admintable" align="center">		        	
			<tr>
				<td class="key" align="right" style="width:240px">
					<?php echo JText::_( 'Please choose Language to duplicate' ); ?>:
				</td>
				
				<td>
					<?php echo $this->languages; ?>
				</td>				
			</tr>
			<tr>
				<td class="key" align="right" >
					<?php echo JText::_('Override')?>?
					<br/>
					<small><?php echo JText::_('automatically overwritten if the template already exists')?></small>
				</td>
				<td>
					<?php echo JHTML::_('select.booleanlist', 'overwrite', '', 0);?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="left">
					<input type="submit" value="<?php echo JText::_('OK')?>" />
					<input type="button" onclick="window.history.go(-1)" value="<?php echo JText::_('Cancel')?>" />
				</td>
			</tr>
		</table>	
	</fieldset>					
					
	<input type="hidden" name="cid" value="<?php echo $this->cid; ?>" />
	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="view" value="emailtemplates" />
	<input type="hidden" name="task" value="duplicate" />
	<?php echo JHTML::_( 'form.token' ); ?>	
 </form>