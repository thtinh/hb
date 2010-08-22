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

class QuestionnaireController extends JController {

/**

 * Method to display the view

 *

 * @access	public

 */

    function display() {

        parent::display();

    }

    function add() {

    // Get/Create the model

        //$model = & $this->getModel('kit');

        $model =& $this->getModel();

        //echo 'test';

        if (isset($model)){

            echo $model->insertKIT();

        }else{

            echo 'empty';

        }

        

    }

}

?>





