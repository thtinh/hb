<?php // no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<div id="keroform">
    <form id="frmkero" name="frmkero" method="POST" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="option" value="com_kero" />
        <input type="hidden" name="task" value="add" />
        <input type="hidden" name="format" value="raw" />
        <ul>
            <li>
                <label></label>
                <input class="textbox required" type="text" name="name" id="name" title="" />
            </li>
            <li>
                <label>Email</label>
                <input class="textbox required email" type="text" name="email" id="email" title="" />
            </li>
           
            <li>
                <label></label>
                <textarea class="textbox required" name="description" id="description" title=""></textarea>
            </li>
        </ul>
        <hr class="space" />
        <input id="btnSubmit" type="submit" value=""><div id="loading" style="display:none"></div>
        <div class="spacer"></div>
    </form>
</div>
<div id="result"></div>