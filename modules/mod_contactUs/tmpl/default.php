<?php defined('_JEXEC') or die ('Registed access');?>

<script type="text/javascript" language="javascript">
    var myFormValidation;
    window.addEvent('domready', function() {
        myFormValidation = new FormCheck('frmquote',{tipsClass : 'tips_box',submitByAjax:false,display : {addClassErrorToField:1}});
        //remove onSubmit event
        //myFormValidation.validateOnSubmit(false);


    });


</script>
<div id="mod_contact_container">
    <div class="bdy-subpage" id="quoteform">
        <form enctype="multipart/form-data" action="<?php echo $url;?>" method="POST" name="frmquote" id="frmquote">

            <ul>
                <li>
                    <label style="padding-right:20px" for="title">Tiêu đề:</label>
                    <input type="text" title="Xin vui lòng nhập tiêu đề." id="title" name="title" class="validate['required'] lightcurve lightshadow"/>
                </li>
                <li>
                    <textarea title="Xin vui lòng nhập nội dung." id="description" name="description" class="validate['required'] lightcurve lightshadow"></textarea>
                </li>
                <li>
                    <label style="padding-right:12px;" for="name">Họ &amp Tên:</label>
                    <input type="text" title="Xin vui lòng nhập Họ và Tên." id="name" name="name" class="validate['required'] lightcurve lightshadow"/>

                    <label for="email">Email:</label>
                    <input type="text" title="Xin vui lòng nhập địa chỉ email và nhập hợp lệ." id="email" name="email" class="validate['required','email'] lightcurve lightshadow"/>
                </li>
                <li>
                    <!-- BEGIN -  CAPTCHA -->
                    <div id="ja-captcha">
                        <div class="type_captcha"><span><label style="padding-right:10px;" for="textCaptcha">Mã bảo vệ:</label><input title="Xin vui lòng nhập mã bảo vệ" type="text" name="captcha" class="validate['required'] lightcurve lightshadow" id="textCaptcha" tabindex="5" value=""/><div id="err_textCaptcha" style="color: red;"></div></span></div>
                        <img height="30px" alt="Captcha Image" onmousemove="actionjacLoadNewCaptcha('show')" onmouseout="actionjacLoadNewCaptcha()" onclick="jacLoadNewCaptcha(0)" id="jac_image_captcha"  src="index.php?option=com_jacomment&amp;task=displaycaptchaaddnew"/>
                        <div id="jac-refresh-image" style="display:none;"><img alt="" src="images/loading.gif" /></div>

                    </div>
                    <!-- END -  CAPTCHA -->
                <li>
            </ul>

            <input type="submit" value="Gửi tin" id="btnSubmit" class="send-contact"/>
            <div style="display: none;" id="loading">Đang gửi...</div>
            <div id="result" style="display:none" ></div>
        </form>
    </div>
</div>