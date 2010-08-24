<?php
/**
 * @version		$Id: controller.php 14974 2010-02-21 14:32:22Z ian $
 * @package		Joomla
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * Contact Component Controller
 *
 * @static
 * @package		Joomla
 * @subpackage	Contact
 * @since 1.5
 */
class AjaxController extends JController {

    function getSponsorList() {
        require_once(JPATH_COMPONENT.DS.'sponsorshelper.php');
        return array();
    }
    function getContentCategory($catid=0) {
        require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
        $catid = (!$catid) ? JRequest::getVar('catid') : $catid;
        $limit = 8;

        $limitstart = JRequest::getVar('limitstart',0);
        $db	=& JFactory::getDBO();
        //dieu kien la phai lay bai viet publish
        $where = $this->_getWhereContentByCat($db,$catid);
        //lay dieu kien cho order by co hit hoac khong co hit va sap xep bai viet theo thu tu giam dan
        $ordering = $this->_getOrdering($mostview);
        //lay query trong ham getQueryContent ra
        $query = $this->_getQueryContent();

        $query = $query . $where .' ORDER BY '. $ordering;

       
        $db->setQuery($query, $limitstart, 0);
        //function getIDetails se tra ra mot mang row co cac link,title,introtext,image
        $rows = $this->_getDetails($db);
        jimport('joomla.html.pagination');
        $pagination = new JPagination(count($rows), $limitstart, $limit);
        
        $result = array();
        $result["content"] = $rows;
        $result["pagination"] = $pagination;
        return $result;
    }
    private function _getOrdering($mostview) {
        // Ordering
        if($mostview == 1)
            $ordering = 'a.hits DESC';
        else
            $ordering = 'a.title ASC, a.created ASC';

        return $ordering;
    }
    private function _getCatId($catid) {
        if ($catid) {
            $ids = explode( ',', $catid );//cắt chuỗi, cứ thấy dấu phẩy là tách ra
            JArrayHelper::toInteger( $ids );//chuyển nguyên mảng $ids sang kiểu số int
            $catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
        }
        return ($catid ? $catCondition : '');
    }
    private function _getDetails(&$db) {
        $rows = $db->loadObjectList();
        $rc = count($rows);
     
        for($i = 0;$i < $rc; $i++) {
            
            //hien thi du'ng ca'c ky tu dac biet trong html
            $rows[$i]->title = htmlspecialchars($rows[$i]->title);
            $rows[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($rows[$i]->slug,$rows[$i]->catslug,$rows[$i]->sectionid));
            $introtext = strip_tags($rows[$i]->introtext);
            //$rows[$i]->introtext = preg_replace("/{[^}]*}/","",$rows[$i]->introtext);
            $rows[$i]->introtext = $introtext;
            $rows[$i]->showmore = JRoute::_(ContentHelperRoute::getCategoryRoute($rows[$i]->catid,$rows[$i]->sectionid));
        }

        return $rows;
    }
     //function nay du`ng de lay query ra
    private function _getQueryContent() {
        $query = 'SELECT a.*,cc.image AS image,' .
                ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
                ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
                ' FROM #__content AS a' .
                //' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id'.
                ' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
                ' INNER JOIN #__sections AS s ON s.id = a.sectionid' ;

        return $query;

    }
    private function _getWhereContentByCat(&$db,$catid) {
        $user		=& JFactory::getUser();
        $aid		= $user->get('aid', 0);
        $contentConfig = &JComponentHelper::getParams( 'com_content' );
        $access		= !$contentConfig->get('show_noauth');

        $nullDate	= $db->getNullDate();
        $date =& JFactory::getDate();
        $now = $date->toMySQL();
        //lay cate id ma nguoi dung nhap bo vao trong cau query
        $cateid = $this->_getCatId($catid);

        $where	=' WHERE a.state = 1'. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
                . ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
                .' AND s.id > 0' .
                ($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
                $cateid.
                //' AND f.content_id IS NULL '.
                ' AND s.published = 1' .
                ' AND cc.published = 1' ;

        return $where;
    }
    function display() {
        $result = null;
        switch ($this->getTask()) {
            //index.php?option=com_contact&task=category&id=0&Itemid=4
            case 'SponsorList':
                $result = $this->getContentCategory();
                break;
            case 'ToBeAdvised':
                break;
        }
        echo json_encode($result);
    }

   
}
