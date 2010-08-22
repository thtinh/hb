<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

class QuestionnaireModelQuestionnaire extends JModel {
    function getKIT() {
        $db =& JFactory::getDBO();
        $query = 'select * from #__questionnaire';
        $db->setQuery( $query );
        $data = $db->loadResult();
        return $data;
    }
    function insertKIT() {
        $name = JRequest::getVar('name');
        $email = JRequest::getVar('email');
        $content = JRequest::getVar('description');
        $body = "
                        <html>
                        <head>
                        <title></title>
                        </head>
                        <body>
                        <p>Có khách muốn hỏi bạn:</p>
                        <div>
                        <p>
                            <b>Họ và Tên:   </b>$name<br/><br/>
                            <b>Email:       </b>$email<br/><br/>
                            <b>Nội dung     </b>$content<br/>
                        </p>
                        </div>
                        </body>
                        </html>
                    ";

        $fulltext = "<p>
                            <b>Họ và Tên:   </b>$name<br/><br/>
                            <b>Email:       </b>$email<br/><br/>
                     </p>";
        $db =& JFactory::getDBO();
        $item =& JTable::getInstance('content');

        $item->title	 	= $content;
        $item->fulltext		= $fulltext;
        $item->catid	 	= 7;
        $item->sectionid 	= 4;

        $date =& JFactory::getDate();

        $item->created		= $date->toMySQL();
        $item->created_by	= 62;
        $item->publish_up	= $date->toMySQL();
        $item->publish_down	= $db->getNullDate();
        $item->state		= 0;

        //only send an email if the post was successful
        if ($item->store()) {
            $from = 'contact@nhakhoabenthanh.com';
            $fromname = 'Nha Khoa Bến Thành';
            $recipient[] = 'uyen@vxg.vn';
            $subject = 'Nha Khoa Bến Thành - câu hỏi';

            
            $mode = 1;
            $mailer =& JFactory::getMailer();
            $mailer->setSender( array(0=> $from, 1=>$fromname) );
            $mailer->addRecipient( $recipient );
            $mailer->setSubject( $subject );
            //we are sending html email
            $mailer->ContentType = "text/html";

            $mailer->setBody( $body );
            $mailer->Send();
        }

    }
}
?>
