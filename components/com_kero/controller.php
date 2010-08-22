<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
jimport('joomla.application.component.controller');
/**
 * Description of kit_controller
 *
 * @author thtinh
 */
class KeroController extends JController {
    /**
     * Method to display the view
     *
     * @access	public
     */
    function display() {
        parent::display();
    }
    function submit() {
        
        $name = trim(JRequest::getVar("name"));
        $email = trim(JRequest::getVar("email"));
        $content = JRequest::getVar("description");
        $db =& JFactory::getDBO();
        $item =& JTable::getInstance('content');
        $content .= '<br/>';
        $content .= "Người gửi: <span class=\"email\">$name</span> - ";
        $content .= "Email: <span class=\"email\">$email</span>";
        $item->title	 	= "Tin nhắn mới";
        $item->introtext	= $content;
        $item->catid	 	= 10;
        $item->sectionid 	= 4;

        $date =& JFactory::getDate();
        
        $item->created		= $date->toMySQL();
        $item->created_by	= 63;
        $item->publish_up	= $date->toMySQL();
        $item->publish_down	= $db->getNullDate();
        $item->state		= 0;
        $view = & $this->getView( 'kero', 'html' );
        $respond = "";

        ($item->store()) ? $respond="successful" : $respond="unsuccessful";            
        
        $view->setLayout('sent');
        $view->display($respond);
    }

}
?>


