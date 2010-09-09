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
        global $mainframe;
        $params = $this->_getMenuParams();
        $pageNav = $this->get('Pagination');
        $data = $this->get('Kids');
        $this->assignRef('kids', $data);
        $this->assign('pageNav', $pageNav);
        $this->assign('params', $params);
        parent::display($tpl);
    }

    function displayResult($tpl = null) {
        $params = $this->_getMenuParams();
        $pageNav = $this->get('Pagination');
        $data = $this->get('Kids');
        if (count($data) > 0) {
            $this->assign('selectedIllness',  $this->get('Illness'));
            $this->assign('selectedYear',  $this->get('Year'));
            $this->assignRef('kids', $data);
            $this->assign('pageNav', $pageNav);
            $this->assign('params', $params);

            parent::display($tpl);
        }
        else {
            $this->assign('params', $params);
            parent::display("noresult");
        }
    }

    function _getMenuParams() {
        global $mainframe;
        $params = &$mainframe->getParams('com_kid');
        $menus = &JSite::getMenu();
        $menu = $menus->getActive();

        // because the application sets a default page title, we need to get it
        // right from the menu item itself
        if (is_object($menu)) {
            $params = new JParameter($menu->params);
            if (!$params->get('page_title')) {
                $params->set('page_title', JText::_('Kid Directory'));
            }
        } else {
            $params->set('page_title', JText::_('Kid Directory'));
        }


        return $params;
    }

    function displayDetail($tpl = null, $pagination = null) {
        global $mainframe;
        $params = $this->_getMenuParams();
        $pageNav = $this->get('Pagination');
        $data = $this->get('Kid');
        $dispatcher = & JDispatcher::getInstance();

        $this->assign('params', $params);
        $this->assignRef('plugin', $results);
        $this->assignRef('kid', $data);
        parent::display($tpl);
    }

}

?>
