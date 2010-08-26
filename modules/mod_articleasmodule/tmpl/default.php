<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>

<?php
  $config=&JFactory::getConfig();
  $sef=$config->getValue("sef");
?>    

<?php
  // get the parameter values
  $item_id = $params->get('item_id');
  $show_head = $params->get('show_head');
  $head_link = $params->get('head_link');
  $head_pre_css = $params->get('head_pre_css');
  $head_post_css = $params->get('head_post_css');
  $article_pre_css = $params->get('article_pre_css');
  $article_post_css = $params->get('article_post_css');
  $read_more = $params->get('read_more');
  $read_more_css = $params->get('read_more_css');
  $read_more_text = $params->get('read_more_text');
?>

<?php foreach ($items as $item) {
	$link = "index.php?option=com_content&view=article&id=".$item->id.":".$item->alias."&catid=".$item->catid.":".$item->catalias;
	if($item_id){
	  $link .= "&Itemid=".$item_id;
	}
	if ($sef==1){
		$link = JRoute::_($link);
	}
?>	
  
<?php if($show_head==1) { 
    echo $head_pre_css;
?>  

<?php if ($head_link==1) {?>
  <a href=<?php echo $link; ?>>
<?php } ?>

<?php echo $item->title; ?>

<?php if ($head_link==1) {?>
  </a>
<?php } ?>

<?php 
    echo $head_post_css;
  } 
?>

<?php
  echo $article_pre_css;
?>

<?php echo $item->introtext; ?>

<?php
  if ($read_more){
?>
  <a class='<?php echo $read_more_css; ?>' href='<?php echo $link; ?>'>
  <?php echo $read_more_text; ?>
  </a>
<?php  	
  }
?>

<?php  
  echo $article_post_css;
?>

<?php
 }
?>
