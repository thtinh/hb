<?php
defined ( '_JEXEC' ) or die ();
/*
# ------------------------------------------------------------------------
# JA Comments component for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2004-2010 JoomlArt.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2. CSS / JS are Copyrighted Commercial,
# bound by Proprietary License of JoomlArt. For details on licensing, 
# Please Read Terms of Use at http://www.joomlart.com/terms_of_use.html.
# Author: JoomlArt.com
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# Redistribution, Modification or Re-licensing of this file in part of full, 
# is bound by the License applied. 
# ------------------------------------------------------------------------
*/
jimport ( 'joomla.application.component.model' );

class JACommentModelModerator extends JModel {

	function __construct() {
		parent::__construct ();
	}

	function getTotal($where_more = '', $joins = '') {
		$db = JFactory::getDBO ();
		
		$query = "SELECT count(u.id) FROM #__users as u" . "\n  $joins" . "\n WHERE 1=1 $where_more";
		$db->setQuery ( $query );
		return $db->loadResult ();
	}
	
	function getItems($where_more = '', $limit = 0, $limitstart = 0, $order = '', $fields = '', $joins = '') {
		$db = JFactory::getDBO ();
		
		if (! $order) {
			$order = ' u.id';
		}
		
		if ($fields)
			$fields = "u.*,$fields ";
		else
			$fields = 'u.*';
		
		if (! $limit)
			$limit = 100;
		
		$sql = "SELECT $fields " 
		. "\n FROM #__users as u " 
		. "\n $joins"
		. "\n WHERE 1=1 $where_more" 
		. "\n ORDER BY $order " 
		. "\n LIMIT $limitstart, $limit";

		$db->setQuery ( $sql ); //echo $db->getQuery ( $sql ), '<br>';
		return $db->loadObjectList ();
	}
	
	function parse(&$items){
		$count=count($items);
		if($count>0){
			for($i=0;$i<$count;$i++){
				$item = & $items[$i];
				$item->params=new JParameter($item->params);
			}
		}
	}
	
	function getItem($cid = array(0)) {
		
		$edit = JRequest::getVar ( 'edit', true );
		if (! $cid || @! $cid [0]) {
			$cid = JRequest::getVar ( 'cid', array (0 ), '', 'array' );
		
		}
		$this->_getTable ();
		JArrayHelper::toInteger ( $cid, array (0 ) );
		if ($edit) {
			$this->_table->load ( $cid [0] );
		}
		
		return $this->_table;
	}
	
	function _getVars() {
		
		global $mainframe;
		
		$option = 'moderator';
		
		$list = array ();
		$list ['filter_order'] = $mainframe->getUserStateFromRequest ( $option . '.filter_order', 'filter_order', 'u.username', 'cmd' );
		
		$list ['filter_order_Dir'] = $mainframe->getUserStateFromRequest ( $option . '.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		
		$list ['limit'] = $mainframe->getUserStateFromRequest ( $option . 'list_limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
		
		$list ['limitstart'] = $mainframe->getUserStateFromRequest ( $option . '.limitstart', 'limitstart', 0, 'int' );
				
		
		$list ['group'] = $mainframe->getUserStateFromRequest ( $option . '.group', 'group', 'moderator', 'string' );
		
		return $list;
	}
}

?>