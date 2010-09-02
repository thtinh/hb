<?php
/**
 * Kids View for Kid Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Kids View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class KidViewKids extends JView {
    /**
     * Kids view display method
     * @return void
     **/
    function display($tpl = null , $filter_division = 0, $filter_department = 0 ) {
   
        

        JToolBarHelper::title(   JText::_( 'Kid Manager' ), 'generic.png' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();
        
        // Get data from the model
       
        $items		= & $this->get( 'Data');
        $pageNav = & $this->get('Pagination');
        $searchword = $this->get('SearchWord');
        $this->assign('searchword',$searchword);
        $this->assignRef('items', $items);
        $this->assign('pageNav', $pageNav);
        parent::display($tpl);
    }
}