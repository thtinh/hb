<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$count = 0;
$document =& JFactory::getDocument();
$document->addScript('templates/vxg_hb/js/iCarousel.js');
$document->addScript('templates/vxg_hb/js/carousel_impl.js');
?>
<div class="carousel<?php echo $params->get( 'moduleclass_sfx' ) ?> curve lightshadow">

    <?php if ($headerText) : ?>
    <div class="carousel_header">
        <?php echo $headerText ?> 
        <span>
        <a href="" id="carousel_link0" class="herolink active">&nbsp;&nbsp;&nbsp;&nbsp;</a>
        <a href="" id="carousel_link1" class="herolink">&nbsp;&nbsp;&nbsp;&nbsp;</a>
        <a href="" id="carousel_link2" class="herolink">&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </span>
    </div>
    <?php endif;?>
    <div id="carousel_content">
        <?php foreach($list as $item) : ?>

        <div class="carouselitem<?php echo $params->get( 'moduleclass_sfx' );?>">
                <?php
                echo modSponsorsHelper::renderBanner($params, $item);
                $count++;
                ?>
            <p><b><?php echo $item->name;?></b><br/><?php echo $item->description;?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($footerText) : ?>
    <div class="carouselfooter<?php echo $params->get( 'moduleclass_sfx' ) ?>">
            <?php echo $footerText ?>
    </div>
    <?php endif; ?>
</div>

