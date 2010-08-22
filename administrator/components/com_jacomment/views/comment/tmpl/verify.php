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
defined ( '_JEXEC' ) or die ( 'Restricted access' );


$row = $this->row;
if(!$row) {
	$email = '';
	$payment_id = '';
} else {
	$email = $row['email'];
	$payment_id = $row['payment_id'];
}
?>
<script type="text/javascript">
	function submit_license_key(){		
		if($('email').value.trim()=='') {
			alert('<?php echo JText::_('Please enter your Email or Username');?>');
			return false;
		}		
		if($('payment_id').value.trim()=='') {
			alert('<?php echo JText::_('Please enter your Payment ID');?>');
			return false;
		}
		return true;
	}
</script>
<fieldset class="adminform TopFieldset">
	<?php echo $this->menu();?>
</fieldset>
<br/>
<form name="adminForm" id="adminForm" action="index.php" method="post">
  <input type="hidden" name="option" value="com_jacomment" />
  <input type="hidden" name="view" value="comment" />
  <input type="hidden" name="task" value="verify" />
  <fieldset>
  <legend><?php echo JText::_('Verify Your License');?></legend>
  <table align="center" width="50%">
    <tr>
      <td align="left" title="<?php echo JText::_("License for domains" );?>::<?php echo JText::_("License for domains desc" );?>"><?php echo JText::_("License for domains" );?>:</td>
      <td align="left"><?php echo $_SERVER ['HTTP_HOST']; ?></td>
    </tr>
    <tr>
      <td align="left" title="<?php echo JText::_("Email or Username" );?>::<?php echo JText::_("Email or Username Desc" );?>"><?php echo JText::_("Email or Username" );?>:</td>
      <td align="left"><input type="text" name="email" id="email" value="<?php echo $email; ?>" size="50" /></td>
    </tr>
    <tr>
      <td align="left" title="<?php echo JText::_("Payment ID" );?>::<?php echo JText::_("Payment ID desc" );?>"><?php echo JText::_("Payment ID" );?>:</td>
      <td align="left"><input type="text" name="payment_id" id="payment_id" value="<?php echo $payment_id; ?>" size="50" /></td>
    </tr>
    <tr>
      <td align="left" colspan="2"><input type="submit"  value="<?php echo JText::_('Submit')?>" onClick="return submit_license_key();"/></td>
    </tr>
  </table>
  </fieldset>
</form>
