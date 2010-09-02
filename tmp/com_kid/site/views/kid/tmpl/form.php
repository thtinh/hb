<?php 
defined('_JEXEC') or die('Restricted access'); 

?>
<style type="text/css">
body{background-color: #f8ffe5;font-size: 13px;color:#6d832c}
.staff_detail{background-color: #f8ffe5;}
.avatar{float:left;width: 25%;margin-right: 10px;}
.label{float:left;width: 85px;}
.data{float:left;}
.contact_info{float:left;width:72%;}
.row{padding: 5px 0;border-bottom: 1px solid #dae9aa;height: 16px;}
.title {color: #75A129;font-weight: bold;font-size: 14px;}
.email{color:#E7830B;}
</style>
<div class="staff_detail">
    <?php if ($this->data->avatar) : ?>
    <div class="avatar"><img alt="Staff Image" width="100px" src="<?php echo $this->data->avatar; ?>" title="<?php echo $this->data->name; ?>"></div>
    <? endif; ?>
    <div class="contact_info">
        <div class="title">Contact Information</div>
        <div class="row">
            <div class="label">Name</div>
            <div class="data">: <?php echo $this->data->name; ?></div>
        </div>
        <div class="row">
            <div class="label">Designation</div>
            <div class="data">: <?php echo $this->data->designation; ?></div>
        </div> 
        <div class="row">
            <div class="label">Division</div>
            <div class="data">: <?php echo $this->division; ?></div>
        </div>
        <?php if ($this->department) : ?>
        <div class="row">
            <div class="label">Department</div>
            <div class="data">: <?php echo $this->department; ?></div>
        </div>
        <? endif; ?>
        <div class="row">
            <div class="label">Email</div>
            <div class="data email">: <a class="email" href="mailto:<?php echo $this->data->email; ?>"><?php echo $this->data->email; ?></a></div>
        </div>
        <div class="row">
            <div class="label">Tel</div>
            <div class="data">: <?php echo $this->data->tel; ?></div>
        </div>
        <?php if ($this->data->interest) : ?>
        <div class="row">
            <div class="label">Interest</div>
            <div class="data">: <?php echo $this->data->interest; ?></div>
        </div>
        <? endif; ?>
    </div>
</div>
