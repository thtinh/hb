<?php
/**
* @version 1.5.x
* @package JoomVision Project
* @email webmaster@joomvision.com
* @copyright (C) 2008 http://www.JoomVision.com. All rights reserved.
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('stylesheet','jvslideshow.php?id='.$module->id.'&width='.$params->get('width').'&height='.$params->get('height').'&background='.$params->get('background'),'modules/mod_jvslideshow/assets/css/');

?>
<script type="text/javascript">
	function startSlideshow<?php echo $module->id; ?>() {
		var mySlideshow<?php echo $module->id; ?> = new gallery($('mySlideshow<?php echo $module->id; ?>'), {
			timed: <?php if($params->get('autorun')==1) : echo "true"; else :echo "false"; endif; ?>,
			defaultTransition: "<?php echo $params->get('transition'); ?>",
			baseClass: 'jdSlideshow',
			embedLinks: <?php if($params->get('linkable')) : echo "true"; else : echo "false"; endif; ?>,
			showArrows: <?php if($params->get('arrows')) : echo "true"; else : echo "false"; endif; ?>,
			showInfopane: <?php if($params->get('showtitle') || $params->get('showdescription')) : echo "true"; else : echo "false"; endif; ?>,
			showCarousel: false,
			fadeDuration: <?php echo $params->get('duration',500); ?>,
			delay: <?php echo $params->get('delay',5000); ?>
		});
	}
	window.addEvent('domready',startSlideshow<?php echo $module->id; ?>);
</script>
<div style="display: none;">Developed by <a title="Joomla Templates, Joomla Extentions" href="http://www.joomvision.com">JoomVision.com</a></div>
<div id="mySlideshow<?php echo $module->id; ?>">
<?php foreach($slides as $slide) : ?>
<div class="imageElement">
	<h3><?php if($params->get('showtitle')) : echo $slide->title; else : echo ""; endif; ?></h3>
	<p><?php if($params->get('showdescription')) : echo $slide->description; else : echo ""; endif; ?></p>
	<a href="<?php echo $slide->link; ?>" class="open" target="<?php if($params->get('newwindow')) : echo "_blank"; else :  echo ""; endif; ?>" title="<?php echo $slide->title; ?>"></a>
	<img src="<?php echo $slide->path; ?>" class="full" alt="<?php echo $slide->title; ?>" />
	<img src="<?php echo $slide->path; ?>" class="thumbnail" width="100" height="100" alt="<?php echo $slide->title; ?>" />
</div>
<?php endforeach; ?>
</div>
