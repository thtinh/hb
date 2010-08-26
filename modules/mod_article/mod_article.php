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

// no direct access
defined('_JEXEC') or die('Restricted access');


JHTML::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers');
require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'query.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

require_once (JPATH_BASE.DS.'components'.DS.'com_content'.DS.'models'.DS.'article.php');


jimport('joomla.application.component.helper');


require_once ('view.html.php');

$id 	= (int) $params->get('id', 0);

$articleModel= new ContentModelArticle;
$articleModel->setId($id);

$articleView = new ContentViewArticle2;
$articleView->addTemplatePath(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'views'.DS.'article'.DS.'tmpl');

$articleView->setModel($articleModel,"true");



// Send the display
$articleView->display();



