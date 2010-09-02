<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<style type="text/css">
    .limit{display:none;}
</style>
<form action="index.php" method="post" name="adminForm">
    <div id="editcell">
        <table>
            <tr>
                <td width="100%">
                    <?php echo JText::_('Enter Kid Name'); ?>:
                    <input type="text" name="search" id="search" value="<?= $this->searchword; ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('Filter by kid name'); ?>"/>
                    <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
                    <button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
                </td>
            </tr>
        </table>
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="1%">
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>
                    <th width="2%">
                        <?php echo JText::_('ID'); ?>
                    </th>

                    <th width="3%">
                        <?php echo JText::_('Avatar'); ?>
                    </th>
                    <th width="20%">
                        <?php echo JText::_('Kid Name'); ?>
                    </th>

                    <th width="10%">
                        <?php echo JText::_('Date of Birth'); ?>
                    </th>

                    <th width="10%">
                        <?php echo JText::_('Illness'); ?>
                    </th>
                    <th width="5%">
                        <?php echo JText::_('Created'); ?>
                    </th>
                </tr>
            </thead>
            <?php
                        $k = 0;
                        for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                            $row = &$this->items[$i];
                            $checked = JHTML::_('grid.id', $i, $row->id);
                            $link = JRoute::_('index.php?option=com_Kid&controller=Kid&task=edit&cid[]=' . $row->id);
            ?>
                            <tr class="<?php echo "row$k"; ?>">
                                <td align="center">
                    <?php echo $checked; ?>
                        </td>
                        <td align="center">
                    <?php echo $row->id; ?>
                        </td>

                        <td align="center">
                            <img width="80px" src="<?php echo '../' . $row->avatar; ?>" alt="Kid's image" title="<?php echo $row->name; ?>" />
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
                        </td>
                        <td>
                            <a><?php echo $row->dob; ?></a>
                        </td>
                        <td>
                            <a><?php echo $row->illness; ?></a>
                        </td>


                        <td>
                    <?php echo $row->created; ?>
                        </td>
                    </tr>
            <?php
                            $k = 1 - $k;
                        }
            ?>
                        <tr>
                            <td colspan="10">
                    <?php echo $this->pageNav->getListFooter(); ?>
                </td>
            </tr>
        </table>
    </div>

    <input type="hidden" name="option" value="com_kid" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="Kid" />
</form>
