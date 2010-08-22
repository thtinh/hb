<?php // no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHTML::_ ( 'script', 'quote.js', 'components/com_questionnaire/assets/' );
JHTML::_ ( 'script', 'validate.js', 'components/com_questionnaire/assets/' );
JHTML::_ ( 'script', 'date-en-GB.js', 'components/com_questionnaire/assets/' );
JHTML::_ ( 'stylesheet', 'quote.css', 'components/com_questionnaire/assets/' );
JHTML::_ ( 'stylesheet', 'validate.css', 'components/com_questionnaire/assets/' );
?>
<div class="hdr-subpage">Gửi câu hỏi</div>
<div id="quoteform">
    <form id="frmquote" name="frmquote" method="POST" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="option" value="com_questionnaire" />
        <input type="hidden" name="task" value="add" />
        <input type="hidden" name="format" value="raw" />
        <ul>
            <li>
                <label>* Họ và Tên</label>
                <input class="textbox required" type="text" name="name" id="name" title="Xin vui lòng nhập Họ và Tên." />
            </li>
            <li>
                <label>* Email</label>
                <input class="textbox required email" type="text" name="email" id="email" title="Xin vui lòng nhập địa chỉ email." />
            </li>
           
            <li>
                <label>* Nội dung</label>
                <textarea class="textbox required" name="description" id="description" title="Xin vui lòng nhập nội dung."></textarea>
            </li>
        </ul>
        <hr class="space" />
        <input id="btnSubmit" type="submit" value="Gửi câu hỏi"><div id="loading" style="display:none">Đang gửi...</div>
        <div class="spacer"></div>
    </form>
</div>
<div id="result">
	Cám ơn bạn đã gửi câu hỏi đến chúng tôi. Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất.
</div>