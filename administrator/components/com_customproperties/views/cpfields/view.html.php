<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.3 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Field Management View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewCpfields extends JView
{
    /**
     * CP Fields view display method
     * @return void
     **/
    function display($tpl = null)
    {
        switch($this->getLayout()){
          case 'edit' :
            //$model = & JModel::getInstance('cpfield','custompropertiesModel');
            $model = $this->getModel('cpfield');
            $field = $model->getData();
            $values = $model->getValues();

            $this->assignRef('field', $field);
            $this->assignRef('values', $values);
            break;

          default:
            $model =& $this->getModel('cpfields');
            $items = $model->getList();
            $page = $model->getPagination();

            $this->assignRef( 'items', $items );
            $this->assignRef( 'page', $page );
          }
        parent::display($tpl);
    }
}
