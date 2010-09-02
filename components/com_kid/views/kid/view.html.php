<?php
/**
 * Kid View for Kid Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport( 'joomla.application.component.view');
jimport('joomla.html.pagination');
/**
 * HTML View class for the Kid Component
 *
 * @package		Joomla.Tutorials
 * @subpackage	Components
 */
class KidViewKid extends JView {

    function display($tpl = null, $pagination = null) {
        $data = $this->get( 'Kids' );
    
        $this->assignRef('items', $data);
        $this->assign('pageNav', $pageNav);
        parent::display($tpl);
    }

}
?>
