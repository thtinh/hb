<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jimport( 'joomla.application.component.view');
/**
 * Description of viewhtml
 *
 * @author thtinh
 */
class QuestionnaireViewQuestionnaire extends JView {

    function display($tpl = null) {
        $data = $this->get( 'questionnaire' );
        $this->assignRef( 'data',	$data );

        parent::display($tpl);
    }
}
?>
