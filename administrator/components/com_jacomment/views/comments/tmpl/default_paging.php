<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<?php global $javconfig, $mainframe, $option;//print_r($this->pagination);exit;?>
<div class="jav-page-links" id="jav-page-links-0">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<div class="jav-display-limit">
	<?php if($this->pagination->total>0){?>
		<label for="limit"><?php echo JText::_("Display")?> # </label>
		<?php echo $this->getListLimit($this->lists['limitstart'], $this->lists['limit'], $this->lists['order']); ?>
	<?php }?>
</div>
<div class="clear"></div>