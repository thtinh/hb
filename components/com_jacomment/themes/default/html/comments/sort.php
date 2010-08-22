<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>
<?php if($enableSortingOptions && $this->totalAll >0) { ?>
<div id="jac-sort">
	<?php echo JText::_("Sort by: ");?>&nbsp;
	<a href="javascript:sortComment('date',this)"  <?php if($defaultSort == "date"){ if($defaultSortType == "ASC"){ echo 'class="jac-sort-by-active-asc"';echo ' title="' . JText::_("Date Ascending, latest comment on top").'"';}else{echo 'class="jac-sort-by-active-desc"';echo ' title="'.JText::_("Date  descending, latest comment in bottom").'"';}}else{echo 'class="jac-sort-by"';echo ' title="'. JText::_("Date Ascending, latest comment on top").'"';}?> id="jac-sort-by-date"><?php echo JText::_("Date");?></a>&nbsp;
	<a href="javascript:sortComment('voted',this)" <?php if($defaultSort == "voted"){ if($defaultSortType == "ASC"){ echo 'class="jac-sort-by-active-asc"';echo ' title="' . JText::_("Most rated on top").'"';}else{echo 'class="jac-sort-by-active-desc"';echo ' title="' . JText::_("Most rated in bottom").'"';}}else{echo 'class="jac-sort-by"';echo ' title="'. JText::_("Most rated on top").'"';}?> id="jac-sort-by-voted"><?php echo JText::_("Rating");?></a>&nbsp;						
</div>
<?php }?>
<?php if($defaultSort){?>
	<input type="hidden" value="<?php echo $defaultSort;?>" id="orderby" name="orderby" />			
<?php }?>