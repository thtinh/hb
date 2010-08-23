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
jimport('joomla.application.component.view');
phocagalleryimport('phocagallery.image.image');
phocagalleryimport('phocagallery.image.imagefront');
phocagalleryimport('phocagallery.file.filethumbnail');

class PhocaGalleryViewCategory extends JView {

    function display($tpl = null) {
        JRequest::setVar('view', 'category' );
        $model		= &$this->getModel();
        $selectedCategory = JRequest::getVar('id');
        $model->setId($selectedCategory);
        $items = $model->getData();
        $catInfo = $model->getCategory();
        foreach ($items as $item){
            $item->link = PhocaGalleryImageFront::displayCategoryImageOrNoImage($item->filename,"medium");
            $item->albumDescription = ($catInfo->description) ? $catInfo->description : "";
        }
        
        
        $this->assign( 'data',	json_encode($items) );
       
        parent::display("json");
    }

}

