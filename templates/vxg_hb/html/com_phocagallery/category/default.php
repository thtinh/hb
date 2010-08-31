<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
$user = &JFactory::getUser();
$db = &JFactory::getDBO();
$menu = &JSite::getMenu();
$selectedCategory = JRequest::getVar('id');
$document = & JFactory::getDocument();
$document->addScript(JURI::base() . '/templates/vxg_hb/js/iCarousel.js');
$document->addScript(JURI::base() . '/templates/vxg_hb/js/json_remote.js');

/*Hardcode ! i know it's bad but i'm too lazy to do anything good :( */
$selectedBackground = "";
switch ((int)$selectedCategory){
  case 1: $selectedBackground = "anh-hoat-dong";break;
  case 2: $selectedBackground = "anh-su-kien";break;
  case 3: $selectedBackground = "anh-truyen-thong";break;
  default:$selectedBackground = "anh-su-kien";break;
}
//$selectedCategory = split(":", $selectedCategory);
// All categories -------------------------------------------------------
$query = 'SELECT cc.title AS text, cc.id AS id, cc.parent_id as parentid, cc.alias as alias, cc.access as access, cc.accessuserid as accessuserid'
        . ' FROM #__phocagallery_categories AS cc'
        . ' WHERE cc.published = 1'
        . ' AND cc.approved = 1'
        . ' AND cc.parent_id =0'
        . ' ORDER BY cc.ordering';

$db->setQuery($query);
$categories = $db->loadObjectList();
$query = 'SELECT cc.title AS text, cc.id AS id, cc.parent_id as parentid, cc.alias as alias, cc.access as access, cc.accessuserid as accessuserid'
        . ' FROM #__phocagallery_categories AS cc'
        . ' WHERE cc.published = 1'
        . ' AND cc.approved = 1'
        . ' AND cc.parent_id=' . (int) $selectedCategory
        . ' ORDER BY cc.ordering';
$db->setQuery($query);
$childCategories = $db->loadObjectList();


foreach ($categories as $value) {
    $value->link = JRoute::_(PhocaGalleryRoute::getCategoryRoute($value->id, $value->alias));
    if ($value->id == (int) $selectedCategory) {
        $value->active = 1;
    }
}
?>
<div id="album">
<?php if ($this->params->get('show_page_title')) : ?>
        <div class="title-page">
            <div class="title-page-line componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
<?php echo $this->escape($this->params->get('page_title')); ?>
        </div>
    </div>
<?php endif; ?>
    <div id ="album-left" class="<?=$selectedBackground?> span-9 clearfix">
        <ul>
<?php foreach ($childCategories as $mycategory) : ?>
<?php if ($mycategory->parentid != 0): ?>
                    <li id="<?= $mycategory->id ?>-album" class="album-left"><a href="#"><?= $mycategory->text ?></a></li>
            <?php endif; ?>
<?php endforeach; ?>
        </ul>
    </div>

    <div class="span-15 last clearfix">
        <div id="album-menu">
            <ul>
<?php foreach ($categories as $mycategory) : ?>
<?php if ($mycategory->parentid == 0): ?>
                        <li class="<?php echo ($mycategory->active) ? "active" : "inactive"; ?>"><a href="<?= $mycategory->link ?>"><?= $mycategory->text ?></a></li>
                <?php endif; ?>
<?php endforeach; ?>
                </ul>
            </div>
            <div id="album-content">
              
                <div id="intro">
                    
                </div>
                <div id="album-thumbnail">
                    <div id="back" style="float:left">
                        <img src="<?= JURI::base() . '/templates/vxg_hb/images' ?>/back-bt.jpg" />
                    </div>

                    <div id="next" style="float:left">
                        <img src="<?= JURI::base() . '/templates/vxg_hb/images' ?>/next-bt.jpg" />
                    </div>
            </div>

        </div>
        <br /> <br /><br /><br /><br /><br /><br />
    </div>

</div>