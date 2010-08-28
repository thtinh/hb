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

$cp_config = $this->parameters;

$got_JoomFish = file_exists(JPATH_ROOT .DS.'administrator'.DS.'components'.DS.'com_joomfish') ? 1 : 0 ;

$list = array ();
$yesno = array (
	JHTML::_('select.option','0',JText::_('No')),
	JHTML::_('select.option', '1', JText::_('Yes'))
);

$lists['use_cp_css']	 		= JHTML::_('select.genericlist', $yesno, 'cfg_use_cp_css',			'class="inputbox" size="1"', 'value', 'text', $cp_config['use_cp_css'] == "" ? 		'1'	: $cp_config['use_cp_css']);
$lists['show_page_title'] 		= JHTML::_('select.genericlist', $yesno, 'cfg_show_page_title',		'class="inputbox" size="1"', 'value', 'text', $cp_config['show_page_title']);
$lists['show_section'] 			= JHTML::_('select.genericlist', $yesno, 'cfg_show_section', 		'class="inputbox" size="1"', 'value', 'text', $cp_config['show_section']);
$lists['show_create_date']		= JHTML::_('select.genericlist', $yesno, 'cfg_show_create_date', 	'class="inputbox" size="1"', 'value', 'text', $cp_config['show_create_date'] == "" ? '0' : $cp_config['show_create_date']);
$lists['show_tags'] 			= JHTML::_('select.genericlist', $yesno, 'cfg_show_tags', 			'class="inputbox" size="1"', 'value', 'text', $cp_config['show_tags']);
$lists['linked_tags'] 			= JHTML::_('select.genericlist', $yesno, 'cfg_linked_tags', 		'class="inputbox" size="1"', 'value', 'text', $cp_config['linked_tags']);
$lists['show_content_element']	= JHTML::_('select.genericlist', $yesno, 'cfg_show_content_element','class="inputbox" size="1"', 'value', 'text', $cp_config['show_content_element'] == "" ? '1' : $cp_config['show_content_element']);
$lists['show_tag_name'] 		= JHTML::_('select.genericlist', $yesno, 'cfg_show_tag_name', 		'class="inputbox" size="1"', 'value', 'text', $cp_config['show_tag_name']);
$lists['show_result_summary']	= JHTML::_('select.genericlist', $yesno, 'cfg_show_result_summary', 'class="inputbox" size="1"', 'value', 'text', $cp_config['show_result_summary']);
$lists['linked_result_summary'] = JHTML::_('select.genericlist', $yesno, 'cfg_linked_result_summary','class="inputbox" size="1"','value', 'text', $cp_config['linked_result_summary']);
$lists['search_unauthorized'] 	= JHTML::_('select.genericlist', $yesno, 'cfg_search_unauthorized', 'class="inputbox" size="1"', 'value', 'text', $cp_config['search_unauthorized'] == "" ? '1' : $cp_config['search_unauthorized']);
$lists['search_archived'] 		= JHTML::_('select.genericlist', $yesno, 'cfg_search_archived', 	'class="inputbox" size="1"', 'value', 'text', $cp_config['search_archived'] == "" ? '1' : $cp_config['search_archived']);
$lists['show_content_element_label'] = JHTML::_('select.genericlist', $yesno, 'cfg_show_content_element_label', 'class="inputbox" size="1"', 'value', 'text', $cp_config['show_content_element_label'] == "" ? '0' : $cp_config['show_content_element_label']);

$format = array(
	JHTML::_('select.option', '0', JText::_('Tag ID')),
	JHTML::_('select.option', '1', JText::_('Tag Name'))
);
$lists['url_format'] 			= JHTML::_('select.genericlist', $format, 'cfg_url_format', 'class="inputbox" size="1"', 'value', 'text', $cp_config['url_format'] == "" ? '0' :  $cp_config['url_format']);

$use_itemid = array (
	JHTML::_('select.option', '0', JText::_('None')),
	JHTML::_('select.option', '1', JText::_('Current page'))
);
$lists['use_itemid'] 			= JHTML::_('select.genericlist', $use_itemid, 'cfg_use_itemid', 'class="inputbox" size="1"', 'value', 'text', $cp_config['use_itemid'] == "" ? '1' :  $cp_config['use_itemid']);

$access = array (
	JHTML::_('select.option', '0', JText::_('Public')),
	JHTML::_('select.option', '1', JText::_('Registered')),
	JHTML::_('select.option', '2', JText::_('Special'))
);
$lists['result_acl'] 			= JHTML::_('select.genericlist', $access, 'cfg_result_acl', 'class="inputbox" size="1"', 'value', 'text', $cp_config['result_acl']);

$view = array(
	JHTML::_('select.option', '0', JText::_('Title only')),
	JHTML::_('select.option', '1', JText::_('Title + Plain text')),
	JHTML::_('select.option', '2', JText::_('Title + HTML intro'))
);
$lists['view'] = JHTML::_('select.genericlist', $view, 'cfg_view', 'class="inputbox" size="1"', 'value', 'text', $cp_config['view'] == "" ? '2' : $cp_config['view']);


$search_headers_elements = array(
	JHTML::_('select.option', '0', JText::_('Nothing')),
	JHTML::_('select.option', '1', JText::_('Search Keyword')),
	JHTML::_('select.option', '2', JText::_('Matching Results')),
	JHTML::_('select.option', '3', JText::_('Tag')),
	JHTML::_('select.option', '4', JText::_('Ordering'))
);
$lists['search_header_tl'] 		= JHTML::_('select.genericlist', $search_headers_elements, 'cfg_search_header_tl', 'class="inputbox" size="1"', 'value', 'text', $cp_config['search_header_tl'] == "" ? "1" : $cp_config['search_header_tl'] );
$lists['search_header_tr'] 		= JHTML::_('select.genericlist', $search_headers_elements, 'cfg_search_header_tr', 'class="inputbox" size="1"', 'value', 'text', $cp_config['search_header_tr'] == "" ? "2" : $cp_config['search_header_tr'] );
$lists['search_header_bl'] 		= JHTML::_('select.genericlist', $search_headers_elements, 'cfg_search_header_bl', 'class="inputbox" size="1"', 'value', 'text', $cp_config['search_header_bl'] == "" ? "3" : $cp_config['search_header_bl'] );
$lists['search_header_br']		= JHTML::_('select.genericlist', $search_headers_elements, 'cfg_search_header_br', 'class="inputbox" size="1"', 'value', 'text', $cp_config['search_header_br'] == "" ? "4" : $cp_config['search_header_br'] );

$lists['use_joomfish'] 		= JHTML::_('select.genericlist', $yesno, 'cfg_use_joomfish', 		'class="inputbox" size="1"', 'value', 'text', $cp_config['use_joomfish'] 	== "" ? '1' : $cp_config['use_joomfish'] );
$lists['frontend_tagging'] 	= JHTML::_('select.genericlist', $yesno, 'cfg_frontend_tagging', 	'class="inputbox" size="1"', 'value', 'text', $cp_config['frontend_tagging']);
$lists['editing_level'] 	= JHTML::_('select.genericlist', $access, 'cfg_editing_level', 		'class="inputbox" size="1"', 'value', 'text', $cp_config['editing_level'] 	== "" ? '1' : $cp_config['editing_level']);

$lists['image_thumbnail'] 	= JHTML::_('select.genericlist', $yesno, 'cfg_image_thumbnail', 	'class="inputbox" size="1"', 'value', 'text', $cp_config['image_thumbnail'] == "" ? '1' : $cp_config['image_thumbnail'] );
$lists['keep_aspect'] 		= JHTML::_('select.genericlist', $yesno, 'cfg_keep_aspect', 		'class="inputbox" size="1"', 'value', 'text', $cp_config['keep_aspect'] 	== "" ? '1' : $cp_config['keep_aspect']);
$lists['debug'] 			= JHTML::_('select.genericlist', $yesno, 'cfg_debug', 				'class="inputbox" size="1"', 'value', 'text', $cp_config['debug'] 			== "" ? '0' : $cp_config['debug'] );

$orders = array (
	JHTML::_('select.option', 'newest', JText::_('Newest First')),
	JHTML::_('select.option', 'oldest', JText::_('Oldest First')),
	JHTML::_('select.option', 'alpha', JText::_('Alphabetical')),
	JHTML::_('select.option', 'category', JText::_('Section/Category'))
);
$lists['ordering'] 			= JHTML::_('select.genericlist', $orders, 'cfg_default_ordering', 'id="search_ordering" class="inputbox"', 'value', 'text', $cp_config['default_ordering']);

$script_pos = array (
	JHTML::_('select.option', 'auto', JText::_('Auto')),
	JHTML::_('select.option', 'head', JText::_('Head')),
	JHTML::_('select.option', 'embed', JText::_('Embedded'))
);
$lists['script_pos'] 		= JHTML::_('select.genericlist', $script_pos, 'cfg_script_position', 'class="inputbox"', 'value', 'text', $cp_config['script_position'] == "" ? 'auto' : $cp_config['script_position']);

$ord_by_fields = array(
	JHTML::_('select.option', '0', JText::_('Title')),
	JHTML::_('select.option', '1', JText::_('Title Alias'))
);
$lists['order_field'] 		= JHTML::_('select.genericlist', $ord_by_fields , 'cfg_ordering_field', 'class="inputbox" size="1"', 'value', 'text', $cp_config['ordering_field'] != "" ? $cp_config['ordering_field'] : '0');

?>
<form action="index.php" method="post" name="adminForm">
<?php

JToolBarHelper::title(JText::_('Custom Properties Configuration'), 'config.png');
JToolBarHelper::save("saveConfig");

if (!$this->is_writable) {
	echo JText::_('WANRNINGCANTSAVE');
}

jimport('joomla.html.pane');
$pane =& JPane::getInstance('Tabs');
echo $pane->startPane( 'cpconfig' );
echo $pane->startPanel( JText::_('General'), 'general-page' );

?>
	<table class="adminlist">
	<tr>
		<th>Param</th>
		<th>Value</th>
		<th>Description</th>
		<th>Default</th>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Page class') ?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_pageclass_sfx" value="<?php echo $cp_config['pageclass_sfx'];?>"/>
		</td>
		<td>
			<?php echo JText::_('Page class') ?>.
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Page title') ?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_header" value="<?php echo $cp_config['header'];?>"/>
		</td>
		<td>
			<?php echo JText::_('PAGETITLETIP') ?>.
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Show page title') ?>:
		</td>
		<td>
			<?php echo $lists['show_page_title'];?>
		</td>
		<td>
			<?php echo JText::_('SHOWPAGETITLETIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes') ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('Script Position') ?>:
		</td>
		<td>
			<?php echo $lists['script_pos'];?>
		</td>
		<td>
			<?php echo JText::_('SCRIPTPOSTIP') ?>.
		</td>
		<td>
			Auto
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Use Joomfish') ?>:
		</td>
		<td>
			<?php echo $got_JoomFish ? $lists['use_joomfish'] : 'JoomFish Not Installed';?>
		</td>
		<td>
			<?php echo JText::_('USEJFTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Search unauthorized articles') ?>:
		</td>
		<td>
			<?php echo $lists['search_unauthorized'];?>
		</td>
		<td>
			<?php echo JText::_('SEARCHUNAUTHTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Search archived articles') ?>:
		</td>
		<td>
			<?php echo $lists['search_archived'];?>
		</td>
		<td>
			<?php echo JText::_('SEARCHARCHTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Search result access') ?>:
		</td>
		<td>
			<?php echo $lists['result_acl'];?>
		</td>
		<td>
			<?php echo JText::_('SEARCHRESULTTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Public') ?>
		</td>
	</tr>
	<tr>
		<td colspan="4"><b><?php echo JText::_('Frontend tagging') ?></b></td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Frontend tagging') ?>:
		</td>
		<td>
			<?php echo $lists['frontend_tagging'];?>
		</td>
		<td>
			<?php echo JText::_('FRONTENDEDITTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('No') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Frontend editing access') ?>:
		</td>
		<td>
			<?php echo $lists['editing_level'];?>
		</td>
		<td>
			<?php echo JText::_('FRONTENDEDITACCESSTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Registered') ?>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Use Itemid') ?>:
		</td>
		<td>
			<?php echo $lists['use_itemid'];?>
		</td>
		<td>
			<?php echo JText::_('ITEMIDTIP') ?>.
		</td>
		<td>
			Current Page
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Results per page') ?>:
		</td>
		<td>
			<input type="text" size="3" name="cfg_limit" value="<?php echo $cp_config['limit'];?>"/>
		</td>
		<td>
			<?php echo JText::_('RESPERPAGETIP') ?>.
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Bind to Section(s)') ?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_search_sections" value="<?php echo preg_replace( '/[^0-9\,]/', '',$cp_config['search_sections']);?>"/>
		</td>
		<td>
			<?php echo JText::_('SECTIONSTIP') ?>.
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Default results ordering') ?>:
		</td>
		<td>
			<?php echo $lists['ordering'];?>
		</td>
		<td>
			&nbsp;
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Ordering field') ?>:
		</td>
		<td>
			<?php echo $lists['order_field'];?>
		</td>
		<td>
			<?php echo JText::_('ORDERFIELDTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Title') ?>
		</td>
	</tr>
	</table>
<?php
	echo $pane->endPanel();
	echo $pane->startPanel(JText::_('Search Results'), 'result-page');
?>
	<table class="adminlist">
	<tr>
		<th>Param</th>
		<th>Value</th>
		<th>Description</th>
		<th>Default</th>
	</tr>
	<tr>
		<td colspan="4"><b><?php echo JText::_('Element rendering')?></b></td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Use customproperties.css') ?>:
		</td>
		<td>
			<?php echo $lists['use_cp_css'];?>
		</td>
		<td>
			<?php echo JText::_('USECPCSSTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Show content element label')?>:
		</td>
		<td>
			<?php echo $lists['show_content_element_label'];?>
		</td>
		<td>
			<?php echo JText::_('SHOWCELABELTIP')?>.
		</td>
		<td>
			<?php echo JText::_('No')?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Show section name') ?>:
		</td>
		<td>
			<?php echo $lists['show_section'];?>
		</td>
		<td>
			<?php echo JText::_('SECTIONNAMETIP') ?>.
		</td>
		<td>
			<?php echo JText::_('No') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Show create date') ?>:
		</td>
		<td>
			<?php echo $lists['show_create_date'];?>
		</td>
		<td>
			<?php echo JText::_('CREATEDATETIP') ?>.
		</td>
		<td>
			<?php echo JText::_('No') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('View')?>:
		</td>
		<td>
			<?php echo $lists['view'];?>
		</td>
		<td>
			<?php echo JText::_('VIEWTIP')?>.
		</td>
		<td>
			Title + HTML intro
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Allowed HTML tags')?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_allowed_tags" value="<?php echo $cp_config['allowed_tags'];?>"/>
		</td>
		<td>
			<?php echo JText::_('HTMLTAGSTIP')?>
		</td>
		<td>
			&lt;h3&gt;&lt;h4&gt;&lt;h5&gt;&lt;a&gt;&lt;p&gt;
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Text length') ?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_text_length" value="<?php echo $cp_config['text_length'];?>"/>
		</td>
		<td>
			<?php echo JText::_('TEXTLENGTHTIP') ?>.
		</td>
		<td>
			200
		</td>
	</tr>
	<tr>
		<td colspan="4"><b><?php echo JText::_('Search header elements')?></b></td>
	</tr>
	<tr>
		<td>
			Top left<br/><?php echo $lists['search_header_tl'];?>
		</td>
		<td>
			Top right<br/><?php echo $lists['search_header_tr'];?>
		</td>
		<td>
			<?php echo JText::_('ELFIRSTROWTIP')?>.
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			Bottom left<br/><?php echo $lists['search_header_bl'];?>
		</td>
		<td>
			Bottom right<br/><?php echo $lists['search_header_br'];?>
		</td>
		<td>
			<?php echo JText::_('ELSECROWTIP')?>.
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="4"><b>Result summary</b></td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Show results summary') ?>:
		</td>
		<td>
			<?php echo $lists['show_result_summary'];?>
		</td>
		<td>
			<?php echo JText::_('RESULTSUMMARYTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('No') ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Show content elements')?>:
		</td>
		<td>
			<?php echo $lists['show_content_element'];?>
		</td>
		<td>
			<?php echo JText::_('SHOWCETIP')?>.
		</td>
		<td>
			<?php echo JText::_('Yes')?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Linked result summary') ?>:
		</td>
		<td>
			<?php echo $lists['linked_result_summary'];?>
		</td>
		<td>
			<?php echo JText::_('LINKEDRESULTSUMAMRYTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes')?>
		</td>
	</tr>
	<tr>
		<td colspan="4"><b><?php echo JText::_('Tags')?></b></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('Show tags') ?>:
		</td>
		<td>
			<?php echo $lists['show_tags'];?>
		</td>
		<td>
			<?php echo JText::_('SHOWTAGSTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes')?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('Linked tags') ?>:
		</td>
		<td>
			<?php echo $lists['linked_tags'];?>
		</td>
		<td>
			<?php echo JText::_('LINKEDTAGSTIP') ?>.
		</td>
		<td>
			<?php echo JText::_('Yes')?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('Show tag name') ?>:
		</td>
		<td>
			<?php echo $lists['show_tag_name'];?>
		</td>
		<td>
			<?php echo JText::_('TAGNAMETIP') ?>.
		</td>
		<td>
			<?php echo JText::_('No')?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('URL format')?>:
		</td>
		<td>
			<?php echo $lists['url_format'];?>
		</td>
		<td>
			<?php echo JText::_('URLFORMATTIP')?>.
		</td>
		<td>
			<?php echo JText::_('Tag ID')?>
		</td>
	</tr>
	</table>
<?php
	echo $pane->endPanel();
	echo $pane->startPanel(JText::_('Thumbnails'), 'thumbs-page');
?>
	<table class="adminlist">
	<tr>
		<th>Param</th>
		<th>Value</th>
		<th>Description</th>
		<th>Default</th>
	</tr>
	<tr>
		<td colspan="4"><b><?php echo JText::_('Thumbnails')?></b></td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Show image thumbnail')?>:
		</td>
		<td>
			<?php echo $lists['image_thumbnail'];?>
		</td>
		<td>
			<?php echo JText::_('SHOWIMGTHUMBTIP')?>.
		</td>
		<td>
			<?php echo JText::_('Yes')?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Thumbnail width')?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_thumb_width" value="<?php echo $cp_config['thumb_width'];?>"/>
		</td>
		<td>
			<?php echo JText::_('THUMBWIDTHTIP')?>.
		</td>
		<td>
			100
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Thumbnail height')?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_thumb_height" value="<?php echo $cp_config['thumb_height'];?>"/>
		</td>
		<td>
			<?php echo JText::_('THUMBHEIGHTTIP')?>.
		</td>
		<td>
			100
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Preserve image aspect')?>:
		</td>
		<td>
			<?php echo $lists['keep_aspect'];?>
		</td>
		<td>
			<?php echo JText::_('PRESERVEIMGTIP')?>.
		</td>
		<td>
			<?php echo JText::_('Yes')?>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_('Image quality')?>:
		</td>
		<td>
			<input type="text" size="15" name="cfg_image_quality" value="<?php echo $cp_config['image_quality'];?>"/>
		</td>
		<td>
			<?php echo JText::_('IMAGEQUALITYTIP')?>.
		</td>
		<td>
			75
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('Show debug messages')?>:
		</td>
		<td>
			<?php echo $lists['debug'];?>
		</td>
		<td>
			<?php echo JText::_('SHOWDBGMSGTIP')?>.
		</td>
		<td>
			<?php echo JText::_('No')?>
		</td>
	</tr>
	</table>
<?php
echo $pane->endPanel();
echo $pane->endPane();
?>
<input type="hidden" name="hidemainmenu" value="0"/>
<input type="hidden" name="option" value="com_customproperties" />
<input type="hidden" name="controller" value="cpanel" />
<input type="hidden" name="task" value="" />

</form>
