<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_SITE.DS.'libraries'.DS.'vxghelper'.DS.'helper.php');
$contentHelper = new helper();
JHTML::_('behavior.modal');
?>
<style type="text/css">
    #kid-directory div.row{overflow: hidden}
    #kid-directory div.row.even{background-color: #e7fafe}
    #panel-left {margin-left: auto;margin-right: auto;text-align: center;}
    #panel-left img{min-width: 80px;max-width: 100px;padding:3px;border: 1px solid #ccc;background-color: #fff;margin: 0 30px;}
    #panel-left a{padding: 10px 20px;/*to be changed if the name has more than 1 word*/}

</style>
<div id="kid-directory" class="span-17">
    <?php for ($i = 0;$i<count($this->kids);$i++) :?>
    <div class="row <?= ($i%2 !=0) ? "odd" : "even"?>">
        <div id="panel-left" class="span-6">
            <a class="title" href="index.php?option=com_kid&task=display_detail&cid=<?= $this->kids[$i]->id; ?>"><?= $this->kids[$i]->name; ?></a>
            <img class="lightcurve lightshadow" src="/hb/images/stories/joomla-dev_cycle.png" alt="avatar" title="this is kid's picture"/>
        </div>
        <div id="panel-right" class="span-11 last">
            <b>Illness:</b><?= $this->kids[$i]->illness ?><br/>
            <?= '<p>'.$contentHelper->cutString($this->kids[$i]->text, 500).'</p>' ?>
        </div>
        <span class="clearfix">&nbsp;</span>
    </div>
    <?php endfor; ?>
    <div class="pagination"><?= $this->pageNav->getListFooter();?></div>
</div>