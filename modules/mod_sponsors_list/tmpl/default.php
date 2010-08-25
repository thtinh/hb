<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$count = 0;

?>
<div class="sponsorslist-wrap">
    <img class="trans" src="images/sponsorslist.png" alt=""/>
    <div class="sponsorslist left">      
        <ul id="sponsorlist-1">
            <?php
//            for($i = 0; $i<count($sponsorslist)/2; $i++){
//                echo '<li>';
//                echo '<a href="'.$sponsorslist[$i]->link.'">'.$sponsorslist[$i]->title.'</a>';
//                echo '</li>';
//            }
            ?>

        </ul>
    </div>
    <div class="sponsorslist right">
        <ul id="sponsorlist-2">
             <?php
//            for($i = count($sponsorslist)/2; $i<count($sponsorslist); $i++){
//                echo '<li>';
//                echo '<a href="'.$sponsorslist[$i]->link.'">'.$sponsorslist[$i]->title.'</a>';
//                echo '</li>';
//            }
            ?>
        </ul>
    </div>
    
    <div id="pagination">
        
    </div>
    <div class="clear"></div>
</div>
