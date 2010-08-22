<?php
/*
# ------------------------------------------------------------------------
# JA Comment plugin for Joomla 1.5
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

defined('_JEXEC') or die( 'Restricted access' );

/**
 *
 *
 *
 */
class JElementJaparamhelper extends JElement
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
    var $_name = 'Japaramhelper';
    var $theme = 'default';
    var $links = '';

    function fetchElement( $name, $value, &$node, $control_name ) {
        if (substr($name, 0, 1) == '@'  ) {
            $name = substr($name, 1);
            if (method_exists ($this, $name)) {
                return $this->$name ($name, $value, $node, $control_name);
            }
        } else {
            $subtype = ( isset( $node->_attributes['subtype'] ) ) ? trim($node->_attributes['subtype']) : '';
            if (method_exists ($this, $subtype)) {
                return $this->$subtype ($name, $value, $node, $control_name);
            }
        }
        return; 
    }
    
    /**
     * Subtype - Categories, multiselect: subtype="categories"
     */
    function categories ( $name, $value, &$node, $control_name ){
        $db = &JFactory::getDBO();
        $query = '
            SELECT 
                c.section,
                s.title AS section_title,
                c.id AS cat_id,
                c.title AS cat_title 
            FROM #__sections AS s
            INNER JOIN #__categories c ON c.section = s.id
            WHERE s.published=1
            AND c.published = 1
            ORDER BY c.section, c.title
            ';
        $db->setQuery( $query );
        $cats = $db->loadObjectList();
        $HTMLCats=array();
        $HTMLCats[0]->id = '';
        $HTMLCats[0]->title = JText::_("ALL CATEGORY");
        $section_id = 0;
        foreach ($cats as $cat) {
            if($section_id != $cat->section) {
                $section_id = $cat->section;
                
                $cat->id = $cat->section;
                $cat->title = $cat->section_title;
                $optgroup = JHTML::_('select.optgroup', $cat->title, 'id', 'title');
                array_push($HTMLCats, $optgroup);
            }
            $cat->id = $cat->cat_id;
            $cat->title = $cat->cat_title;
            array_push($HTMLCats, $cat);
        }
        return JHTML::_('select.genericlist',  $HTMLCats, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:95%;" multiple="multiple" size="10"', 'id', 'title', $value );
    }
    
    /**
    * Subtype - Categories, multiselect: subtype="menus"
    */
    function menus ( $name, $value, &$node, $control_name ){
        $all->value = '';
        $all->text = JText::_("ALL MENU");
        
        $menus = JHTML::_('menu.linkoptions');
        array_unshift($menus, $all); 
        
        return JHTML::_('select.genericlist',  $menus, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:95%;" multiple="multiple" size="10"', 'value', 'text', $value );
    }       
	
    function getParamValue($group, $param, $default){
    	require_once(JPATH_BASE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'comments.php');
        $model = new JACommentModelComments();
        $paramValue = $model->getParamValue( $group, $param ,$default);
        return $paramValue;   
    }
    
    function getLinkButton($fileName){
    	global $mainframe;
    	$templateJaName = $this->getParamValue('layout', 'theme' , 'default');
    							
		$templateDirectory  =  JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS."com_jacomment".DS."themes".DS.$templateJaName.DS."html";									
		 if(file_exists($templateDirectory.DS.$fileName)){
		 	return $templateDirectory.DS.$fileName;	
		 }else{		 			 	
		 	if(file_exists('components/com_jacomment/themes/'.$templateJaName.'/html/'.$fileName)){		 			
				return 'components/com_jacomment/themes/'.$templateJaName.'/html/'.$fileName;
		 	}else{
		 		return 'components/com_jacomment/themes/default/html/'.$fileName; 	
		 	}
		 }			
    }
} 