<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$count = 0;
?>
<ul id="news-list" class="<?php echo $params->get('moduleclass_sfx'); ?>">
    <?php foreach ($rows as $row) :  ?>
    <li>
        <?php if($showthumbnail && ($row->images !="")) : ?><img width="60" src="images/stories/<?php $row->images; ?>" alt="<?php echo $row->title; ?>"><?php endif; ?>
        <a href="<?php echo $row->link; ?>"><?php echo $row->title; ?></a><br/>
        <span class="news-intro"><?php echo ($trimtext==0) ? $row->introtext : $contentHelper->cutString($row->introtext,$trimtext); ?></span>
    </li>
    <?php endforeach; ?>
</ul>
