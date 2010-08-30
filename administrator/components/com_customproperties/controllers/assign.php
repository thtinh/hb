<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.4 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Custom Properties Component Controller - Assign
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesControllerAssign extends JController
{

  /**
   * constructor (registers additional tasks to methods)
   * @return void
   */
  function __construct()
  {
    parent::__construct();

    // Register Extra tasks
    $this->registerTask( 'addProperties',	 'assignProp' );
    $this->registerTask( 'replaceProperties','assignProp' );
    $this->registerTask( 'deleteProperties', 'assignProp' );

  }

  /**
   * Method to display the view
   *
   * @access    public
   */
  function display()
  {

  	$view = $this->getView('assign', 'html');
	$view->setModel( $this->getModel( 'assign', 	'CustompropertiesModel' ), true );
	$view->setModel( $this->getModel( 'cpfields', 	'CustompropertiesModel' ) );
	$view->setModel( $this->getModel( 'content', 	'CustompropertiesModel' ) );

	$view->display();

  }

  function assignProp(){
    switch($this->getTask()){
      case 'deleteProperties' :
        $action = 'delete';
        break;
      case 'replaceProperties' :
        $action = 'replace';
        break;
      case 'addProperties' :
      default:
        $action = 'add';
        break;
    }


    $return_to = 'index.php?option=com_customproperties&controller=assign&task=showContentItems';
    $ce_name = JRequest::getVar('ce_name', '');
    if($ce_name != "") $return_to .= "&ce_name=$ce_name";
    $section_id = JRequest::getInt('filter_sectionid', 0);
    if($section_id != "0") $return_to .= "&filter_sectionid=$section_id";
    $category_id = JRequest::getInt('filter_categoryid', 0);
    if($category_id != "0" ) $return_to .= "&filter_categoryid=$category_id";

    $limit = JRequest::getInt('limit', '');
    $return_to .= "&limit=$limit";
    $limitstart = JRequest::getInt('limitstart', '');
    $return_to .= "&limitstart=$limitstart";

    $model = & $this->getModel('assign');
    $model->assignCustomProperties($action);

    $this->setRedirect($return_to);
  }

}

