<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$count = 0;
?>
<ul id="news-list">
    <?php foreach ($rows as $row) :  ?>
    <li>
        <img width="60" src="images/stories/<?php echo $row->images; ?>" alt="<?php echo $row->title; ?>">
        <a href="<?php echo $row->link; ?>"><?php echo $row->title; ?></a><br/>
        <span class="news-intro"><?php echo $contentHelper->cutString($row->introtext,100); ?></span>
    </li>
    <?php endforeach; ?>
</ul>
