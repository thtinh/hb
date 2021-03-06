<?php

/**
 * Kid View for Kid Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */
jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

/**
 * HTML View class for the Kid Component
 *
 * @package		Joomla.Tutorials
 * @subpackage	Components
 */
class KidViewKid extends JView {

    function display($tpl = null, $pagination = null) {
        // Set up the data to be sent in the response.      
        $data = $this->get( 'Kids' );                    
        echo json_encode($data);
    }

}

?>
