<?php // no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" >
    <head>
        <script type="text/javascript" src="media/system/js/mootools.js"></script>
        <script type="text/javascript" src="modules/mod_contactUs/js/formcheck/formcheck_org3.js"></script>
        <link rel="stylesheet" href="templates/vxg_hb/css/modal-souvenir.css" type="text/css" media="screen" />
        <!--[if IE]>
         <script type="text/javascript" src="templates/vxg_hb/js/border-radius-iefix.js"></script>
        <link rel="stylesheet" href="templates/vxg_hb/css/ie.css" type="text/css" media="screen" />
        <![endif]-->


        <script type="text/javascript">
            window.addEvent('domready', function() {
                $("description").addEvent("keydown",countit);
                $("description").addEvent("change",countit);
                var myFormValidation;
                myFormValidation = new FormCheck('frmkero',{
                    tipsClass : 'tips_box',
                    submitByAjax:true,
                    display : {addClassErrorToField:1}
                });

//                $("frmkero").addEvent("submit",function(e){
//                    if (myFormValidation.isFormValid){
//                        //prevent the submit event
//                        new Event(e).stop();
//                        $("loading").setStyle('display','block');
//                        this.send({
//                            update : $('result'),
//                            onComplete:function(){
//                                $("loading").setStyle('display','none');
//                            }
//                        })
//                    }
//                });
            });
            function countit(){
                descriptionvalue=$("description").getProperty("value");
                $("chars_left_notice").setText(1000 - descriptionvalue.length);
            }
        </script>
    </head>
    <body style="background-color: transparent">
        <div id="keroform">

            <form id="frmkero" name="frmkero" method="POST" action="index.php" enctype="multipart/form-data">

                <input type="hidden" name="option" value="com_kero" />
                <input type="hidden" name="task" value="submit" />
                <input type="hidden" name="format" value="raw" />
                <input onblur="if (this.value == '') { this.value = 'Your name (required)';}" onfocus="if( this.value == 'Your name (required)') {this.value = '';}"
                       class="validate['required'] lightcurve lightshadow" type="text" name="name" id="name" title="Your name (required)" value="Your name (required)"/>
                <input onblur="if (this.value == '') { this.value = 'Your email (required)';}" onfocus="if( this.value == 'Your email (required)') {this.value = '';}"
                       class="validate['email'] email lightcurve lightshadow" type="text" name="email" id="email" title="Your email (required)" value="Your email (required)"/>
                <textarea onchange="countit()" onkeydown="countit();" cols="37" rows="10" class="validate['required'] lightcurve lightshadow" name="description" id="description" title=""></textarea>
                <span class="numeric" id="chars_left_notice">
                    <strong class="char-counter" id="status-field-char-counter">1000</strong>
                </span>
                <input id="btnSubmit" type="submit" value="Gửi đi"/>
                <div id="loading" style="display:none">loading</div>
                <div class="spacer"></div>
            </form>
        </div>
        <div id="result"></div>
    </body>
</html>