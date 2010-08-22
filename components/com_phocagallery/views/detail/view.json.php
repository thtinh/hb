<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
phocagalleryimport( 'phocagallery.image.image');
phocagalleryimport( 'phocagallery.image.imagefront');
phocagalleryimport( 'phocagallery.file.filethumbnail');

class PhocaGalleryViewDetail extends JView{
    function display($tpl = null) {
        parent::display("json");
    }
    
}

