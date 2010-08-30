<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="search_right">

    <div class="search_heard">TÌM KIẾM</div>

    <div class="search">
        <form id="searchForm" name="searchForm" action="index.php" method="get" onsubmit="return doSearch();">
            <div class="search_field_1">
                <div class="field_label">Ứng dụng</div>
                <?php echo $lists['application'];?>
            </div>

            <div class="search_field_2">
                <div class="field_label">Sản phẩm</div>
                <?php echo $lists['product']; ?>
            </div>

            <div class="text_search">
                <div class="field_label">Từ khóa</div>
                <input id="txtSearch" type="text" class="inputbox" name="searchword" value="">
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
<script type="text/javascript">
function doSearch(){
    if (document.searchForm.searchword.value !='') {
        document.searchForm.option.value = 'com_search';
        document.searchForm.method = "post";
    }
    else {
        document.searchForm.option.value = 'com_customproperties';
        document.searchForm.method = "get";
    }
    document.searchForm.submit();
}

</script>