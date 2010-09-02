<?php
/**
 * Staff Controller for Staff Component
 * 
 * @package    	Joomla.Tutorials
 * @subpackage 	Components
 * @license		Commercial
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Staff Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class StaffsControllerStaff extends StaffsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'Staff' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('Staff');

		if ($model->store($post)) {
			$msg = JText::_( 'Staff Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Staff' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_staff';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('Staff');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Staffs could not be Deleted' );
		} else {
			$msg = JText::_( 'Staff(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_staff', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_staff', $msg );
	}
}