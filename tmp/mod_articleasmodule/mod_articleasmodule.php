<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).DS.'helper.php');

// get a parameter from the module's configuration
$args['moduleclass_sfx'] = $params->get('moduleclass_sfx');
$args['article_id'] = $params->get('article_id');

$items = ModArticleAsModule::getArticle($args);

// include the template for display
require(JModuleHelper::getLayoutPath('mod_articleasmodule'));
?>
