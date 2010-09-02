<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$divArray = array();
$deArray = array();
$i = 0;
$db =& JFactory::getDBO();
$query = "select id as value, name as text from #__division where parent = 1";
$db->setQuery($query);
$div_data = $db->loadObjectList();

foreach ($div_data as $division) {

    $divArray[$i]->value = $division->value;

    $divArray[$i]->text = $division->text;

    $query  = "select d2.id as value, d2.name as text from #__division d1
                   left join #__division_map m on d1.id = m.division_id
                   left join #__division d2 on d2.id = m.department_id
                   where d1.parent = 1 and d1.id = $division->value";
    $db->setQuery($query);
    $de_data = $db->loadObjectList();
    $divArray[$i]->departments = $de_data;
    
    $i++;
}
 $i = 1;
$divisions_html[] = JHTML::_('select.option',  '0', '- '. JText::_( 'All Divisions/Departments' ) .' -' );
foreach ($divArray as $div) {
    //$mitems[] = JHTML::_('select.option',  $list_a->id, $list_a->treename );
    $divisions_html[] = JHTML::_('select.option',  $div->value, $div->text );
    if ($div->departments[0]->text) {
        foreach ($div->departments as $de) {
            $divisions_html[] = JHTML::_('select.option',  $de->value, '&nbsp;&nbsp;&nbsp;'.$de->text );
            
        }
    }
 
}

//$divisions_html[] = JHTML::_('select.option',  '0', '- '. JText::_( 'All Divisions/Departments' ) .' -' );
//$divisionslist = array_merge( $divisions_html, $divArray );
$javascript = 'onchange="document.searchForm.division_id.value = this.options[this.options.selectedIndex].value;"';
 $lists['division'] = JHTML::_('select.genericlist',$divisions_html,'division', 'class="input" size="1" '. $javascript, 'value', 'text', "" );
//$javascript = "";
//$lists['division'] = JHTML::_('select.genericlist',$divisionslist,'division', 'class="input" size="1" '. $javascript, 'value', 'text', "" );
?>
<div class="search_bar">
    <ul class="search_tabs">
        <li> <a id="searchby">Search by</a></li>
        <li>
            <a class="select" id="search_0"> Name </a>
        </li>
       
    </ul>
</div>
<form action="index.php" name="searchForm" id="searchForm">
    <div style="display: block;" id="tabSearch_0" class="search_content">

        <div class="search_left">
            <div class="content_left"> Name </div>
            <div class="content_left"> Division/Department </div>
        </div>
        <div class="search_right">
            <div class="content_right"><input name="searchword" id="searchName" type="text" size="20" class="input"></div>
            <div class="content_right">
                <?php echo $lists['division'];?>
            </div>

            <div class="content_right"><input class="submitbutton" type="submit" value="Find" ></div>
        </div>
    </div>
    
    <input id="searchType" type="hidden" name="searchtype" value="<?php echo $searchtype; ?>" />
    <input type="hidden" name="option" value="com_staff" />
    <input type="hidden" name="division_id" value="" />
    <input type="hidden" name="task" value="search" />
</form>