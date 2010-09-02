<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');

?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="col100">
        <fieldset class="adminform">
            <legend><?php echo JText::_('Kid Details'); ?></legend>

            <table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="name">
<?php echo JText::_('Name'); ?>:
                        </label>
                    </td>
                    <td>
                        <input title ="Please enter Kid's name" class="textbox required" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->Kid->name; ?>" />
                    </td>
                </tr>

                <tr>
                    <td width="100" align="right" class="key">
                        <label for="dob">
<?php echo JText::_('Date of Birth'); ?>:
                        </label>
                    </td>
                    <td>
                        <input title="Please enter Kid's Date of Birth" class="textbox required" type="text" name="dob" id="dob" size="50" maxlength="250" value="<?php echo $this->Kid->dob; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="illness">
<?php echo JText::_('Illness'); ?>:
                        </label>
                    </td>
                    <td>
                        <input title="Please enter Kid's Illness" class="textbox required" type="text" name="illness" id="illness" size="50" maxlength="250" value="<?php echo $this->Kid->illness; ?>" />
                    </td>
                </tr>

                <tr>
                    <td width="100" align="right" class="key">
                        <label for="avatar">
                            <?php echo JText::_('Avatar'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="imageurl" id="imageurl" size="50" maxlength="250" value="<?php echo $this->Kid->avatar; ?>">

                        <a rel="{handler: 'iframe', size: {x: 570, y: 400}}" href="index.php?option=com_media&view=images&tmpl=component&e_name=imageurl&noeditor=1"
                           title="Image" class="modal">Change avatar</a>
                    </td>
                </tr>

                <tr>
                    <td width="100" align="right" class="key">
                        <label for="Text">
                            <?php echo JText::_('Text'); ?>:
                        </label>
                    </td>
                    <td>
                        <?php
                        // parameters : areaname, content, width, height, cols, rows
                        echo $this->editor->display('text', $this->Kid->text, '100%', '550', '75', '20');
                        ?>
                    </td>
                </tr>

            </table>
        </fieldset>
    </div>
    <div class="clr"></div>
    
    <input type="hidden" name="created" value="<?php echo $this->Kid->created; ?>" />
    <input type="hidden" name="option" value="com_kid" />
    <input type="hidden" name="id" value="<?php echo $this->Kid->id; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="Kid" />
</form>
