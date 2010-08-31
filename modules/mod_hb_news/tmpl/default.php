<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$count = 0;
?>
<div id="leadingnews">
    <img width="150" src="images/stories/<?=$rows[0]->images; ?>" alt="<?= $rows[0]->title; ?>">
    <a class="newstitle" href="<?= $rows[0]->link; ?>"><?= $rows[0]->title ; ?></a><br/>
    <span class="news-intro"><?= ($trimtext==0) ? $rows[$count]->introtext : $contentHelper->cutString($rows[$count]->introtext,$trimtext); ?></span>
</div>
<ul class="<?php echo $params->get('moduleclass_sfx'); ?>">
    <?php for ($count=1;$count<count($rows);$count++) :  ?>
    <li>        
        <a class="newstitle" href="<?= $rows[$count]->link; ?>"><?= $rows[$count]->title ; ?></a><br/>
    </li>
    <?php endfor; ?>
</ul>
