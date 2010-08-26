<?php
/* 
* Copyright (c) 2009 Adam Florizone. All rights reserved.
* Copyright (c) 2009 Digihaven Technology & Design Canada. All rights reserved. http://www.digihaven.com/
*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2, or (at your option)
 * any later version.

 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with Content Optimizer; see the file COPYING. If not, write to the
 * Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston,
 * MA 02111-1307, USA.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once (JPATH_BASE.DS.'components'.DS.'com_content'.DS.'view.php');

class ContentViewArticle2 extends ContentView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$user		=& JFactory::getUser();
		$document	=& JFactory::getDocument();
		$dispatcher	=& JDispatcher::getInstance();
		$pathway	=& $mainframe->getPathway();
		$params		=& $mainframe->getParams('com_content');
		
		// Initialize variables
		$article	=& $this->get('Article');
		$aparams		=& $article->parameters;
		$params->merge($aparams);

				// !! should thses be an option?
		// Remove the title (Becasue we want to use the Module one)
		$params->set('show_title',false);
		$params->set("show_title", 0);
	  	$params->set("show_intro", 0);
	  	$params->set("show_section", 0);
	  	$params->set("show_category", 0);
	  	$params->set("show_author", 0);
	  	$params->set("show_create_date", 0);
	  	$params->set("show_item_navigation", 0);
	  	$params->set("show_vote", 0);
	  	$params->set("show_icon", 0);
	  	$params->set("show_pdf_icon", 0);
	  	$params->set("show_print_icon", 0);
	  	$params->set("show_email_icon", 0);
	  	$params->set("show_hits", 0);
	  	
		if($this->getLayout() == 'pagebreak') {
			$this->_displayPagebreak($tpl);
			return;
		}

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		if (($article->id == 0))
		{
			$id = JRequest::getVar( 'id', '', 'default', 'int' );
			//return JError::raiseError( 404, JText::sprintf( 'Article # not found', $id ) );
			return;
		}

		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		
		if (!$params->get('intro_only') && ($this->getLayout() == 'default') && ($limitstart == 0))
		{
			$model =& $this->getModel();
			$model->hit();
		}

		// Create a user access object for the current user
		$access = new stdClass();
		//$access->canEdit	= $user->authorize('com_content', 'edit', 'content', 'all');
		//$access->canEditOwn	= $user->authorize('com_content', 'edit', 'content', 'own');
	//	$access->canPublish	= $user->authorize('com_content', 'publish', 'content', 'all');

		// Check to see if the user has access to view the full article
		$aid	= $user->get('aid');

		if ($article->access <= $aid) {
			$article->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));;
		} else {
			return;
		}

		// Search for the {readmore} tag and split the text up accordingly.
		$text = str_replace('<br>', '<br />', $article->text);


		/*
		 * Process the prepare content plugins
		 */
		JPluginHelper::importPlugin('content');
		$results = $dispatcher->trigger('onPrepareContent', array (& $article, & $params, $limitstart));
		
		/*
		 * Handle the metadata
		 */
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		// Get the menu item object
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();

		if (is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'article' && isset($menu->query['id']) && $menu->query['id'] == $article->id) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$article->title);
			}
		} else {
			$params->set('page_title',	$article->title);
		}
		

		/*
		 * Handle display events
		 */
		$article->event = new stdClass();
		$results = $dispatcher->trigger('onAfterDisplayTitle', array ($article, &$params, $limitstart));
		$article->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $article, & $params, $limitstart));
		$article->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterDisplayContent', array (& $article, & $params, $limitstart));
		$article->event->afterDisplayContent = trim(implode("\n", $results));
		
		$print = JRequest::getBool('print');
		if ($print) {
	      $document->setMetaData('robots', 'noindex, nofollow');
	    }
	  	
		$this->assignRef('article', $article);
		$this->assignRef('params' , $params);
		$this->assignRef('user'   , $user);
		$this->assignRef('access' , $access);
		$this->assignRef('print', $print);

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe;

		// Initialize variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$uri		=& JFactory::getURI();
		$params		=& $mainframe->getParams('com_content');

		// Make sure you are logged in and have the necessary access rights
		if ($user->get('gid') < 19) {
			  JResponse::setHeader('HTTP/1.0 403',true);
              JError::raiseWarning( 403, JText::_('ALERTNOTAUTH') );
			return;
		}

		// Initialize variables
		$article	=& $this->get('Article');
		$aparams	=& $article->parameters;
		$isNew		= ($article->id < 1);

		$params->merge($aparams);

		// At some point in the future this will come from a request object
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		// Add the Calendar includes to the document <head> section
		JHTML::_('behavior.calendar');

		if ($isNew)
		{
			// TODO: Do we allow non-sectioned articles from the frontend??
			$article->sectionid = JRequest::getVar('sectionid', 0, '', 'int');
			$db = JFactory::getDBO();
			$db->setQuery('SELECT title FROM #__sections WHERE id = '.(int) $article->sectionid);
			$article->section = $db->loadResult();
		}

		// Get the lists
		$lists = $this->_buildEditLists();

		// Load the JEditor object
		$editor =& JFactory::getEditor();

		// Build the page title string
		$title = $article->id ? JText::_('Edit') : JText::_('New');

		// Set page title
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		// Get the menu item object
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();
		$params->set( 'page_title', $params->get( 'page_title' ) );
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Submit an Article' ));
			}
		} else {
			$params->set('page_title', JText::_( 'Submit an Article' ));
		}
		$document->setTitle( $params->get( 'page_title' ) );

		// get pathway
		$pathway =& $mainframe->getPathWay();
		$pathway->addItem($title, '');

		// Unify the introtext and fulltext fields and separated the fields by the {readmore} tag
		if (JString::strlen($article->fulltext) > 1) {
			$article->text = $article->introtext."<hr id=\"system-readmore\" />".$article->fulltext;
		} else {
			$article->text = $article->introtext;
		}

		$this->assign('action', 	$uri->toString());

		$this->assignRef('article',	$article);
		$this->assignRef('params',	$params);
		$this->assignRef('lists',	$lists);
		$this->assignRef('editor',	$editor);
		$this->assignRef('user',	$user);


		parent::display($tpl);
	}

	function _buildEditLists()
	{
		// Get the article and database connector from the model
		$article = & $this->get('Article');
		$db 	 = & JFactory::getDBO();

		$javascript = "onchange=\"changeDynaList( 'catid', sectioncategories, document.adminForm.sectionid.options[document.adminForm.sectionid.selectedIndex].value, 0, 0);\"";

		$query = 'SELECT s.id, s.title' .
				' FROM #__sections AS s' .
				' ORDER BY s.ordering';
		$db->setQuery($query);

		$sections[] = JHTML::_('select.option', '-1', '- '.JText::_('Select Section').' -', 'id', 'title');
		$sections[] = JHTML::_('select.option', '0', JText::_('Uncategorized'), 'id', 'title');
		$sections = array_merge($sections, $db->loadObjectList());
		$lists['sectionid'] = JHTML::_('select.genericlist',  $sections, 'sectionid', 'class="inputbox" size="1" '.$javascript, 'id', 'title', intval($article->sectionid));

		foreach ($sections as $section)
		{
			$section_list[] = (int) $section->id;
			// get the type name - which is a special category
			if ($article->sectionid) {
				if ($section->id == $article->sectionid) {
					$contentSection = $section->title;
				}
			} else {
				if ($section->id == $article->sectionid) {
					$contentSection = $section->title;
				}
			}
		}

		$sectioncategories = array ();
		$sectioncategories[-1] = array ();
		$sectioncategories[-1][] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
		$section_list = implode('\', \'', $section_list);

		$query = 'SELECT id, title, section' .
				' FROM #__categories' .
				' WHERE section IN ( \''.$section_list.'\' )' .
				' ORDER BY ordering';
		$db->setQuery($query);
		$cat_list = $db->loadObjectList();

		// Uncategorized category mapped to uncategorized section
		$uncat = new stdClass();
		$uncat->id = 0;
		$uncat->title = JText::_('Uncategorized');
		$uncat->section = 0;
		$cat_list[] = $uncat;
		foreach ($sections as $section)
		{
			$sectioncategories[$section->id] = array ();
			$rows2 = array ();
			foreach ($cat_list as $cat)
			{
				if ($cat->section == $section->id) {
					$rows2[] = $cat;
				}
			}
			foreach ($rows2 as $row2) {
				$sectioncategories[$section->id][] = JHTML::_('select.option', $row2->id, $row2->title, 'id', 'title');
			}
		}

		$categories = array();
		foreach ($cat_list as $cat) {
			if($cat->section == $article->sectionid)
				$categories[] = $cat;
		}

		$categories[] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
		$lists['sectioncategories'] = $sectioncategories;
		$lists['catid'] = JHTML::_('select.genericlist',  $categories, 'catid', 'class="inputbox" size="1"', 'id', 'title', intval($article->catid));

		// Select List: Category Ordering
		$query = 'SELECT ordering AS value, title AS text FROM #__content WHERE catid = '.(int) $article->catid.' AND state > ' .(int) "-1" . ' ORDER BY ordering';
		$lists['ordering'] = JHTML::_('list.specificordering', $article, $article->id, $query, 1);

		// Radio Buttons: Should the article be published
		$lists['state'] = JHTML::_('select.booleanlist', 'state', '', $article->state);

		// Radio Buttons: Should the article be added to the frontpage
		if($article->id) {
			$query = 'SELECT content_id FROM #__content_frontpage WHERE content_id = '. (int) $article->id;
			$db->setQuery($query);
			$article->frontpage = $db->loadResult();
		} else {
			$article->frontpage = 0;
		}

		$lists['frontpage'] = JHTML::_('select.booleanlist', 'frontpage', '', (boolean) $article->frontpage);

		// Select List: Group Access
		$lists['access'] = JHTML::_('list.accesslevel', $article);

		return $lists;
	}

	function _displayPagebreak($tpl)
	{
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('PGB ARTICLE PAGEBRK'));

		parent::display($tpl);
	}
}
?>
