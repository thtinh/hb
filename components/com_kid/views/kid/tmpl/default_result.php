<?php // no direct access

?>

<div class="title-page">
    <div class="title-page-line">
        <?=$this->params->get('page_title')?>
    </div>
</div>
<div id="kid-directory" class="span-17">
    <?php for ($i = 0;$i<count($this->kids);$i++) :?>
    <div class="row <?= ($i%2 !=0) ? "odd" : "even"?>">
        <div id="panel-left" class="span-6">
            <a class="title" href="index.php?option=com_kid&task=display_detail&cid=<?= $this->kids[$i]->id; ?>"><?= $this->kids[$i]->name; ?></a>
            <img class="lightcurve lightshadow" src="/hb/images/stories/joomla-dev_cycle.png" alt="avatar" title="this is kid's picture"/>
        </div>
        <div id="panel-right" class="span-11 last">
            <b>Illness:</b><?= $this->kids[$i]->illness ?><br/>
                <?= '<p>'.$contentHelper->cutString($this->kids[$i]->text, 350).'</p>' ?>
        </div>
        <span class="clearfix">&nbsp;</span>
    </div>
    <?php endfor; ?>
    <div class="pagination"><?= $this->pageNav->getListFooter();?></div>
</div>