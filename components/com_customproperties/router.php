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

function CustompropertiesBuildRoute(& $query) {
	$segments = array ();


	if (isset ($query['view'])) {
		$segments[] = $query['view'];
		unset ($query['view']);
	}
	if (isset ($query['task'])) {
		$segments[] = $query['task'];
		unset ($query['task']);
	};
	if (isset ($query['tagName'])) {
		$segments[] = $query['tagName'];
		unset ($query['tagName']);
	};

	return $segments;
}

function CustompropertiesParseRoute($segments) {

	$vars = array ();
	$count = count($segments);


	switch ($segments[0]) {
		case 'tag' :
			$vars['task']	= 'tag';
			if(strpos($segments[$count-1], ':')){
				$vars['tagName'] = $segments[$count-1];
			}
			else{
				$vars['tagId'] = $segments[$count-1];
			}
			break;
		case 'tagging' :
			$vars['view']	= 'tagging';
			break;
	}

	return $vars;
}