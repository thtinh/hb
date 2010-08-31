<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$count = 0;
?>
<ul id="news-list" class="<?php echo $params->get('moduleclass_sfx'); ?>">
    <?php for ($count=0;$count<count($rows);$count++) :  ?>
    <li>
        <?php if($count == 0) : ?><img width="150" src="images/stories/<?=$rows[$count]->images; ?>" alt="<?= $rows[$count]->title; ?>"><?php endif; ?>
        <a href="<?= $rows[$count]->link; ?>"><?= $rows[$count]->title ; ?></a><br/>
        <?php if($count==0) : ?><span class="news-intro"><?= ($trimtext==0) ? $row->introtext : $contentHelper->cutString($rows[$count]->introtext,$trimtext); ?></span><?php endif; ?>
    </li>
    <?php endfor; ?>
</ul>
