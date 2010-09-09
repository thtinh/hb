<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
?>
<div class="title-page">
    <div class="title-page-line">
       <?= $this->params->get('page_title') ?>
    </div>
</div>
<div id="kid-directory" class="span-17">
    <div id="search-noresult">
        <img src="images/noresult.jpg" alt="no result"/>  
        <span style="text-align: center">Quay lại <a href="index.php?option=com_kid&view=kid&Itemid=16">danh sách bé</a></span>
    </div>    
</div>