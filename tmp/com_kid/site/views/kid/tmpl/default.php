<?php // no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHTML::_ ( 'script', 'staff.js', 'components/com_staff/assets/' );
JHTML::_ ( 'script', 'validate.js', 'components/com_staff/assets/' );
JHTML::_ ( 'script', 'date-en-GB.js', 'components/com_staff/assets/' );
JHTML::_ ( 'stylesheet', 'staff.css', 'components/com_staff/assets/' );
JHTML::_ ( 'stylesheet', 'validate.css', 'components/com_staff/assets/' );
JHTML::_('behavior.modal');
$searchtype = $this->searchtype;
if ($searchtype=="") $searchtype="name"; //set default search mode
?>

<div id="staff_directory">
    <div class="tab_bar">
        <ul class="ajax_tabs">
            <li>
                <a id="tabs_0"  onclick="Tabs.showtab(0)" class="select"><?php if ($this->department) echo $this->department->name;else echo $this->division->name;?></a>
            </li>
            <li>
                <a id="tabs_1" onclick="Tabs.showtab(1)"> Search </a>
            </li>
        </ul>
    </div>

    <div class="tabs_content" id="tabs_content_0" style="display:block;">
        <form id="adminForm" action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm">
            <div class="header_content" style="display:none"> Office of the Director (OD)</div>
            <div class="description_content"> <?php echo $this->division->note; ?></div>
            <div class="title_content">
                <div class="title"> <?php echo JHTML::_( 'grid.sort', 'Name', 'name', $this->lists['order_Dir'], $this->lists['order']); ?> </div>
                <div class="title"> <?php echo JHTML::_( 'grid.sort', 'Designation', 'designation', $this->lists['order_Dir'], $this->lists['order']); ?> </div>
                <div class="title"> <?php echo JHTML::_( 'grid.sort', 'Phone', 'tel', $this->lists['order_Dir'], $this->lists['order']); ?>  </div>
                <div class="title"> <?php echo JHTML::_( 'grid.sort', 'Email', 'email', $this->lists['order_Dir'], $this->lists['order']); ?>  </div>
            </div>
            <div class="info_content">
                <?php for ($i=0, $n=count( $this->data ); $i < $n; $i++): ?>
                    <?php $staff = $this->data[$i];?>
                <div class="row <?php if ($i%2 == 0 & $i!=0) echo 'even'; elseif ($i%2 != 0) echo 'odd'; else echo'';?>">
                    <div class="field">
                        <a class="modal" rel="{handler: 'iframe', size: {x: 475, y: 220}}"
                           href="index2.php?option=com_staff&format=raw&task=display_detail&cid=<? echo $staff->id; ?>">
                                   <? echo $staff->name; ?>
                        </a>
                    </div>
                    <div class="field black"> <? echo $staff->designation; ?> </div>
                    <div class="field black textcenter"> <? echo $staff->tel; ?> </div>
                    <div class="field"> <a class="orange" href="mailto:<? echo $staff->email; ?>"><? echo $staff->email; ?> </a></div>
                </div>
                <?php endfor; ?>
            </div>
            
            <?php echo $this->pageNav->getListFooter(); ?>
            <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
             <input type="hidden" name="option" value="com_staff" />
             <input type="hidden" name="task" value="<?php echo $this->task; ?>" />
             <input type="hidden" name="di_id" value="<?php echo $this->di_id; ?>" />
             <input type="hidden" name="de_id" value="<?php echo $this->de_id; ?>" />
             <input type="hidden" name="searchword" value="<?php echo $this->searchword; ?>" />
          
        </form>
    </div>

    <div class="tabs_content" id="tabs_content_1" style="display:none;">   
        <!-- Search Panel -->
        <?php include 'search_form.php';?>
        <?php include 'search_footer.php';?>
    </div>

</div>