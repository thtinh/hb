<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="search_right">

    <div class="search">
        <form id="searchForm" name="searchForm" action="index.php" method="get" onsubmit="return doSearch();">
            <div class="search_field_1">
                <label for="illness">Loại dị tật</label>
                <?php echo $lists['illness'];?>
            </div>

            <div class="search_field_2">
                <label for="year">Năm sinh</label>
                <?php echo $lists['year']; ?>
            </div>

            <div class="text_search">
                <label for="txtSearch">Từ khóa</label>
                <input id="txtSearch" type="text" class="inputbox <?=$css_class?>" name="searchword" value="">
            </div>

            <div class="search_submit">
                <input type="submit" value="Tìm" class="search_button">
            </div>

            <input id="option" type="hidden" name="option" value="com_customproperties"/>
            <input type="hidden" name="view" value="show"/>
            <input type="hidden" name="task" value="show"/>
            <input type="hidden" name="layout" value="default"/>
            <input type="hidden" value="search" name="task">
            <input type="hidden" name="cp_application" value="" />
            <input type="hidden" name="cp_product" value="" />
            <input type="hidden" name="limit" value="5" />
            <input type="hidden" name="Itemid" value="<?php echo $itemid;?>"/>

        </form>

    </div>
</div>
<?php 
$script = "  function doSearch(){  ";
$script .= "  if (document.searchForm.searchword.value !='') {  ";
$script .= "    document.searchForm.option.value = 'com_search';  ";
$script .= "    document.searchForm.method = \"post\";  ";
$script .= "  }  ";
$script .= "  else {  ";
$script .= "    document.searchForm.option.value = 'com_customproperties';  ";
$script .= "    document.searchForm.method = \"get\";  ";
$script .= "  }  ";
$script .= "  document.searchForm.submit();  ";
$script .= " }  ";
$document =& JFactory::getDocument();
$document->addScriptDeclaration($script);

?>