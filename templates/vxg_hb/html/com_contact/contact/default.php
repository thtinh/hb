<?php
/**
 * $Id: default.php 11328 2008-12-12 19:22:41Z kdevine $
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

$cparams = JComponentHelper::getParams ('com_media');
?>
<?php if ( $this->params->get( 'show_page_title', 1 ) && !$this->contact->params->get( 'popup' ) && $this->params->get('page_title') != $this->contact->name ) : ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->params->get( 'page_title' ); ?>
	</div>
<?php endif; ?>
<div id="component-contact">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if ( $this->params->get( 'show_contact_list' ) && count( $this->contacts ) > 1) : ?>
<tr>
	<td colspan="2" align="center">
		<br />
		<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="selectForm" id="selectForm">
		<?php echo JText::_( 'Select Contact' ); ?>:
			<br />
			<?php echo JHTML::_('select.genericlist',  $this->contacts, 'contact_id', 'class="inputbox" onchange="this.form.submit()"', 'id', 'name', $this->contact->id);?>
			<input type="hidden" name="option" value="com_contact" />
		</form>
	</td>
</tr>
<?php endif; ?>
<?php if ( $this->contact->name && $this->contact->params->get( 'show_name' ) ) : ?>
<tr>
	<td width="100%" class="contentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->contact->name; ?>
	</td>
</tr>
<?php endif; ?>
<tr>
	<td>
		<br />
		Mọi thắc mắc xin liên hệ với chúng tôi theo:
		<br /><br />
		<table border="0" cellpadding="10" cellspacing="15" style="margin-left:-15px;border-spacing:15px">
		<tr>
			<td width="42%">
				<span style="color:#016239;font-size:14px"><strong>Bệnh viện Đa khoa Phổ Quang</strong></span><br />
				<table border="0" cellpadding="0" cellspacing="8" style="margin-left:-8px;border-spacing:8px">
				<tr>
					<td class="lbl-contact">Địa chỉ:</td>
					<td>2B Phổ Quang, Phường 2,<br />Quận Tân Bình, TP. Hồ Chí Minh.</td>
				</tr>
				<tr>
					<td class="lbl-contact">Điện thoại:</td>
					<td>(84 + 8) 997 6276</td>
				</tr>
				<tr>
					<td class="lbl-contact">Fax:</td>
					<td>(84 + 8) 842 0410</td>
				</tr>
				<tr>
					<td class="lbl-contact">Email:</td>
					<td><a href="mailto:it@phoquanghospital.com">it@phoquanghospital.com</a></td>
				</tr>
				</table>
			</td>
			<td width="14%">
				<img class="timg" src="templates/vxg_phoquang/images/ico-contact.png" />
			</td>
			<td width="44%">
				<span style="color:#016239;font-size:14px"><strong>Bệnh viện Đa khoa Phổ Quang 1</strong></span><br />
				<table border="0" cellpadding="0" cellspacing="8" style="margin-left:-8px;border-spacing:8px">
				<tr>
					<td class="lbl-contact">Địa chỉ:</td>
					<td>129A Nguyễn Chí Thanh, Phường 9,<br />Quận 5, TP. Hồ Chí Minh.</td>
				</tr>
				<tr>
					<td class="lbl-contact">Điện thoại:</td>
					<td>(84 + 8) 853 7797</td>
				</tr>
				<tr>
					<td class="lbl-contact">Fax:</td>
					<td>(84 + 8) 853 7796</td>
				</tr>
				<tr>
					<td class="lbl-contact">Email:</td>
					<td><a href="mailto:it@phoquanghospital.com">it@phoquanghospital.com</a></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table border="0" cellpadding="10" cellspacing="0" style="border-bottom:1px solid #a6dbd3">
		<tr>
			<td style="color:red">Hoặc theo đường dây nóng:&nbsp;&nbsp; <strong style="font-size:18px">(84 + 8) 2200 8003</strong><br /><br /></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table border="0" width="100%">
		<tr>
			<td></td>
			<td rowspan="2" align="right" valign="top">
			<?php if ( $this->contact->image && $this->contact->params->get( 'show_image' ) ) : ?>
				<div style="float: right;">
					<?php echo JHTML::_('image', 'images/stories' . '/'.$this->contact->image, JText::_( 'Contact' ), array('align' => 'middle')); ?>
				</div>
			<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $this->loadTemplate('address'); ?>
			</td>
		</tr>
		</table>
	</td>
	<td>&nbsp;</td>
</tr>
<?php if ( $this->contact->params->get( 'allow_vcard' ) ) : ?>
<tr>
	<td colspan="2">
	<?php echo JText::_( 'Download information as a' );?>
		<a href="<?php echo JURI::base(); ?>index.php?option=com_contact&amp;task=vcard&amp;contact_id=<?php echo $this->contact->id; ?>&amp;format=raw&amp;tmpl=component">
			<?php echo JText::_( 'VCard' );?></a>
	</td>
</tr>
<?php endif;
if ( $this->contact->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id))
	echo $this->loadTemplate('form');
?>
</table>
</div>
