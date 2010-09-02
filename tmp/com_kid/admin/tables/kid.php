<?php
/**
 * Kid World table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Kid Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableKid extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;


	/**
	 * @var string
	 */
	var $name = null;

        /**
	 * @var string
	 */
	var $dob = null;

        /**
	 * @var string
	 */
	var $illness = null;

        /**
	 * @var string
	 */
	var $avatar = null;

        /**
	 * @var string
	 */
	var $text = null;
	/**
	 * @var string
	 */
	var $created = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableKid(& $db) {
		parent::__construct('#__kid', 'id', $db);
	}
}