<?php

/**
* Joomla Custom Properties
* @package Custom Properties
* @subpackage cptags.php - Custom Properties content mambot
* @version 1.98.1
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.application.plugin.helper');

class plgContentCpTags extends JPlugin {

	/**
	* Content Item ID
	*
	* @var integer
	*/
	var $_cid;
	/**
	* Content Element
	*
	* @var object
	*/
	var $_ce;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgContentCpTags(& $subject) {
		parent::__construct($subject);
		// load plugin parameters
		$this->_plugin = & JPluginHelper::getPlugin('content', 'cptags');
		$this->_params = new JParameter($this->_plugin->params);
		JPlugin::loadLanguage( 'com_customproperties' );
	}

	/**
	* Plugin method with the same name as the event will be called automatically.
	*/
	function onPrepareContent(& $row) {

		global $cp_config;
		// make sure component is installed
		$config_file 	= JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_customproperties' . DS . 'cp_config.php';
		$helper_file 	= JPATH_ROOT . DS . 'components' . DS . 'com_customproperties' . DS . 'helper.php';
		$ce_file	 	= JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_customproperties' . DS . 'contentelement.class.php';
		if (!file_exists($config_file) || !file_exists($helper_file) || !file_exists($ce_file)) {
			echo $row->text .= "<span class=\"alert\">Fatal Error: the component <b>com_customproperties</b> is not/badly installed. " . "Get it at <a href=\"http://www.solidsystem.it\">www.solidsystem.it</a></span>";
			return true;
		} else {
			require $config_file;
			require_once $helper_file;
			require_once $ce_file;
		}
		// get the content element
		$option = JRequest::getVar('option');
		if(!$ce = getContentElementByOption($option)){
			return;
		}
		$this->_ce =& $ce;

		// parameter
		$params 			= & $this->_params;
		$tags_position		= $params->def('tags_position', '0');
		$show_tag_name		= $params->def('show_tag_name', $cp_config['show_tag_name']);
		$linked_tags		= $params->def('linked_tags', 	$cp_config['linked_tags']);
		$url_format			= $params->def('url_format', 	$cp_config['url_format']);

		if($cp_config['use_cp_css']){
			$document = JFactory::getDocument();
			$document->addStyleSheet('components/com_customproperties/css/customproperties.css');
		};

		$frontent_tagging	= $params->def('frontend_tagging', 	$cp_config['frontend_tagging']);
		$editing_level		= $params->def('editing_level', 	$cp_config['editing_level']);

		//append tag to meta only when in "detail / article" view
		if(JRequest::getVar('view','') === $ce->href_view || JRequest::getVar('task', '') === $ce->href_task)  {
			$tag_in_meta 	= $params->def('tag_in_meta', 	'1') ;
		}else{
			$tag_in_meta 	= $params->set('tag_in_meta', 	'0') ;
		}

		// saving item id for replacer
		$this->_cid 	= $row->id;

		// in case we are under PHP 4
		$GLOBALS['botCpTagsContentId'] 	= $row->id;
		$GLOBALS['botCpTagsParams'] 	= & $this->_params;
		$GLOBALS['botCpTagsCe']		 	= & $this->_ce;

		switch ($tags_position) {
			case 0 : // bottom
				$row->text .= showTags($ce, $this->_cid, $params, $tag_in_meta) ;
				break;
			case 1 : // top
				$row->text 	= showTags($ce, $this->_cid, $params, $tag_in_meta) . $row->text;
				break;
			case 2 : // custom
				if (strpos($row->text, 'cptags') === false) {
					return true;
				}
				// expression to search for
				$regex = '/{cptags}/i';
				// find all instances of mambot and put in $matches
				preg_match_all($regex, $row->text, $matches);
				// Count mambots
				$count = count($matches[0]);
				if ($count) {
					$row->text = preg_replace_callback($regex, array (
						'plgContentCpTags',
						'CpTags_replacer'
					), $row->text);
				}
				break;
		}

		unset ($GLOBALS['botCpTagsContentId']);
		unset ($GLOBALS['botCpTagsParams']);
		unset ($GLOBALS['botCpTagsCe']);

		return true;
	}

	/**
	* Replaces the matched {cptags} with the tags
	* @param array An array of matches (see preg_match_all)
	* @return string
	*/
	function CpTags_replacer(& $matches) {

		$text = "";
		$cid 	= $this->_cid ?		$this->_cid : 		$GLOBALS['botCpTagsContentId'];
		$params = $this->_params ? 	$this->_params : 	$GLOBALS['botCpTagsParams'];
		$ce		= $this->_ce ? 		$this->_ce : 		$GLOBALS['botCpTagsCe'];

		if (empty ($cid))
			return $text;
		$text = showTags($ce, $cid, $params, $this->_params->tag_in_meta );
		return $text;

	}

}