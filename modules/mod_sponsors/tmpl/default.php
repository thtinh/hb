<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$count = 0;
?>
<div class="bannergroup<?php echo $params->get( 'moduleclass_sfx' ) ?>">

    <?php if ($headerText) : ?>
    <div class="bannerheader"><?php echo $headerText ?></div>
    <?php endif;?>

    <?php foreach($list as $item) : ?>

    <div class="banneritem<?php echo $params->get( 'moduleclass_sfx' );echo ($count > (int) $params->get( 'count',9))? ' hidden' : ''; ?> curve lightshadow">
            <?php
            echo modSponsorsHelper::renderBanner($params, $item);
            $count++;
            ?>
    </div>
    <?php endforeach; ?>

    <?php if ($footerText) : ?>
    <div class="bannerfooter<?php echo $params->get( 'moduleclass_sfx' ) ?>">
            <?php echo $footerText ?>
    </div>
    <?php endif; ?>
    <div class="clr"></div>
</div>
