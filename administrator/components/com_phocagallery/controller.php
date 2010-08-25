<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
jimport('joomla.application.component.controller');

// Submenu view
$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );
if ($view == '' || $view == 'phocagallerycp') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	
}

if ($view == 'phocagallerys') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys', true);
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');

}

if ($view == 'phocagallerycs') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs', true );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');

}

if ($view == 'phocagalleryt') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Image Rating'), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');

}

if ($view == 'phocagalleryra') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_USERS'), 'index.php?option=com_phocagallery&view=phocagalleryusers');
}

if ($view == 'phocagalleryraimg') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');


}

if ($view == 'phocagallerycos') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos', true);
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');

}

if ($view == 'phocagallerycoimgs') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs', true);


}

if ($view == 'phocagalleryusers') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');


}

if ($view == 'phocagalleryin') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('PHOCAGALLERY_IMAGE_COMMENTS'), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');

}

class PhocaGalleryCpController extends JController
{
	function display() {
		parent::display();
	}
}
?>