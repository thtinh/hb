<?php // no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHTML::_ ( 'script', 'staff.js', 'components/com_staff/assets/' );
JHTML::_ ( 'script', 'validate.js', 'components/com_staff/assets/' );
JHTML::_ ( 'script', 'date-en-GB.js', 'components/com_staff/assets/' );
JHTML::_ ( 'stylesheet', 'staff.css', 'components/com_staff/assets/' );
JHTML::_ ( 'stylesheet', 'validate.css', 'components/com_staff/assets/' );
JHTML::_('behavior.modal');
$searchtype = $this->searchtype;
if ($searchtype=="") $searchtype="name"; //set default search mode
?>

<div id="staff_directory">
<div class="tab_bar">
	<ul class="ajax_tabs">
		<li>
			<a id="tabs_0" href="index.php?option=com_staff">Staff Directory</a>
		</li>
		<li>
			<a id="tabs_1" class="select" onclick="Tabs.showtab(1)"> Search </a>
		</li>
	</ul>
</div>

<div class="tabs_content" id="tabs_content_0" style="display:block;">
	<div class="header_content">Search criteria: <?php echo $this->searchword; ?></div>
        <div class="description_content">  Could not find any staff that match your criteria. Click <a onclick="Tabs.showtab(1)" href="#">here</a> to search again</div>
</div>
<div class="tabs_content" id="tabs_content_1" style="display:none;">
	  <!-- Search Panel -->
        <?php include 'search_form.php';?>
        <?php include 'search_footer.php';?>  
</div>
</div>