<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.6 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die('Restricted access');

$content_element = $this->content_element;
$ce_name = $this->ce_name;

$javascript = 'onchange="if(document.adminForm.filter_sectionid)document.adminForm.filter_sectionid.selectedIndex=0;if(document.adminForm.filter_categoryid)document.adminForm.filter_categoryid.selectedIndex=0;document.adminForm.limitstart.value=0;document.adminForm.submit();"';
$select_contentelement = SelectContentElement('ce_name', $ce_name, $javascript);

$javascript = 'onchange="document.adminForm.filter_categoryid.selectedIndex=0;document.adminForm.limitstart.value=0;document.adminForm.submit();"';
$filter_sectionid = JRequest :: getVar('filter_sectionid', 0, '', 'int');
$select_section = SelectSection($content_element, 'filter_sectionid', $filter_sectionid, $javascript);

$javascript = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit();"';
$filter_categoryid = JRequest :: getVar('filter_categoryid', 0, '', 'int');
$select_category = SelectCategory($content_element, 'filter_categoryid', $filter_sectionid, $filter_categoryid, $javascript);

$items 		= $this->items;
$count 		= count($items);
$pageNav 	= $this->page;
$cpfields 	= $this->cpfields;

$content_id = $this->content_id;
$isEdit = false;
$text_name = '';
if ($content_id) {
	$isEdit = true;
}
?>

<form action="index.php" method="post" name="adminForm">
<?php

$text = $isEdit ? ' View' : '';
$text_name = $isEdit ? "<small><small>[ ". htmlspecialchars_decode($this->item_title)." ]</small></small>" : "";

JToolBarHelper :: title(JText :: _('Assign Custom Properties') . "<small>$text</small> $text_name ", 'assign.png');
JToolBarHelper :: custom("addProperties", "add_link.png", '', JText :: _('Add'), true);
JToolBarHelper :: custom("replaceProperties", "replace_link.png", '', JText :: _('Replace'), true);
JToolBarHelper :: custom("deleteProperties", "delete_link.png", '', JText :: _('Delete'), true);
JToolBarHelper :: custom("showContentItems", "view_link.png", '', JText :: _('View/Edit'), true);
JHTML :: _('behavior.tooltip');
?>
<div id="editcell">
  <table width="100%" class="adminform">
    <thead>
    <tr>
      <td colspan="2">
      	<?php
      	echo $select_contentelement."\n";
      	echo JText::_('Filters');
      	echo $select_section."\n";
      	echo $select_category."\n";
      	echo JText::_('Title');
      	echo "<input type=\"text\" name=\"filter_title\" value=\"\" />\n";
      	?>
      </td>
    </tr>
    </thead>
    <tfoot>
    <tr>
      <td colspan="2"><?php echo $pageNav->getListFooter(); ?></td>
    </tr>
    </tfoot>
    <tr>
      <td valign="top" width="60%">
      <!-- content items -->
      <?php

$index = $k = 0;
?>
      <table width="100%" class="adminlist">
      <thead>
      <tr>
        <th width="5">#</th>
        <th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $count?>);" /></th>
        <th class="title"><?php echo JText::_('Title')?></th>
        <th align="left"><?php echo JText::_('Section')?></th>
        <th align="left"><?php echo JText::_('Category')?></th>
        <th align="left"><?php echo JText::_('Tags')?></th>
        <th align="left">ID</th>
      </tr>
      </thead>

      <tbody>
      <?php

foreach ($items as $row) {
?>
      <tr class="row<?php echo $k?>">
        <td><?php echo $pageNav->getRowOffset( $index ) ?></td>
        <td align="center"><?php echo JHTML::_('grid.id', $index, $row->id ) ?></td>
        <td><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $index ?>','showContentItems')"><?php echo $row->title;?></a></td>
        <td><?php echo $row->section ;?></td>
        <td><?php echo $row->category;?></td>
        <td align="center">
          <?php
          	$assigned_tags = getTags($row->id, $content_element);
          	echo $assigned_tags ? JHTML::tooltip($assigned_tags, JText::_('Assigned tags'), '', JText::_('Tags'), '', false) : JText::_('No tags');?>
        </td>
        <td><?php echo $row->id;?></td>
      </tr>
      <?php

	$k = 1 - $k;
	$index++;
}
?>
      </tbody>
      </table>
      </td>

      <td valign="top" width="40%">
      <!-- custom properties -->
      <table width="100%" class="adminlist">
      <tr>
        <th>Custom Properties</th>
      </tr>
      <tr>
        <td valign="top">

	<?php
	echo "<div class=\"cp_fields\">\n";
	foreach ($cpfields as $row) {
		echo "<div class=\"cp_field\">\n";
		echo "<div class=\"cp_field_label\">". $row->label . "</div>\n";
		switch ($row->field_type) {
			case "text" :
			case "select" :
				echo drawCpInput('select', $row, $content_id, $content_element);
				break;
			case "checkbox" :
				echo drawCpInput('checkbox', $row, $content_id, $content_element);
				break;
			default :
				echo JText :: sprintf('ERRNOTSUPPORTED', $row->name);
				break;
		}
		echo "</div>\n";
	}
	echo "</div>\n";
?>

        </td>
      </tr>
      </table>

      </td>
    </tr>
  </table>
</div>
<input type="hidden" name="hidemainmenu" value="0"/>
<input type="hidden" name="option" value="com_customproperties" />
<input type="hidden" name="controller" value="assign" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />

</form>
