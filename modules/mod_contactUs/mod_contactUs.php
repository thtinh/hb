<?php 
defined('_JEXEC') or die ('Registed access');
require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'helpers'.DS.'jacaptcha'.DS.'jacapcha.php');

$doc =& JFactory::getDocument();
$doc->addScript('modules/mod_contactUs/js/formcheck/lang/en.js');
$doc->addScript('modules/mod_contactUs/js/formcheck/formcheck_org3.js');

$NAME = '';
$EMAIL = '';
$SUBJECT = '';
$MESSAGE = '';
$DEP = '';
$CAPTCHA_TEXT = '';

$NAME = $_POST["name"];
$EMAIL = $_POST["email"];
$SUBJECT = $_POST["title"];
$MESSAGE = $_POST["description"];
$DEP = $_POST["department"];
$CAPTCHA_TEXT = $_POST["captcha"];
$respond='';
$thanksTextColor = "Cám ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất.";

$captcha = new jacapcha();
$captcha->text_entered = $CAPTCHA_TEXT;
$captcha->validateText("addnew");

if ($NAME && $EMAIL && $SUBJECT && $MESSAGE && $captcha->valid_text) {

    $recipient = "thtinh@vxg.vn";
    $mySubject = $SUBJECT;
    $myMessage = 'Bạn nhận được email từ '. $EMAIL ."\n\n". "Nội dung:".$MESSAGE;
    $mailSender = &JFactory::getMailer();
    $mailSender->addRecipient($recipient);

    $mailSender->setSender(array($EMAIL,$NAME));
    $mailSender->addReplyTo(array( $EMAIL, '' ));

    $mailSender->setSubject($mySubject);
    $mailSender->setBody($myMessage);

    if ($mailSender->Send() != true) {
        $myReplacement = '<span style="color: #f00;">' . $errorText . '</span>';
        $respond =  $myReplacement;
    }
    else {
        $respond = '<span style="color: '.$thanksTextColor.';">' . $pageText . '</span>';
    }
    require(JModuleHelper::getLayoutPath('mod_contactUs','sent'));
}
else require(JModuleHelper::getLayoutPath('mod_contactUs'));
?>