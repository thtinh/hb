<div class="details">
    <div class="details_header"><?php echo $this->division->name; ?></div>
    <div class="details_content">
        <ul>
            <?php
            if ($this->departmentlist[0]->name) {
                foreach ($this->departmentlist as $department) {
                    echo '<li>';
                    echo '<a href="index.php?option=com_staff&task=staff_by&di_id='.$this->division->id.'&de_id='.$department->id.'">'.$department->name.'</a>';
                    echo '</li>';
                }
            }
            ?>

        </ul>
    </div>
</div>