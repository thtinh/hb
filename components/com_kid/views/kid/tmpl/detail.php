<?php 
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
?>
<div class="title-page">
    <div class="title-page-line">
        <?=$this->params->get('page_title')?>
    </div>
</div>
<div id="kid-directory" class="span-17">
    <div id="kid-info">
        <a class="title"><?= $this->kid->name ?></a><br/>
        <b>Date of birth:</b>
        <span class="data"><?= $this->kid->dob ?></span><br/>
        <b>Illness:</b>
        <span class="data"><?= $this->kid->illness ?></span>
        <img src="/hb/images/stories/joomla-dev_cycle.png" alt="Kid's image"/>
    </div>
    <div id="kid-description">
        <?= $this->kid->text ?>
    </div>
</div>

