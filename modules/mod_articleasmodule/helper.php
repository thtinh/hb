<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModArticleAsModule {
    
    public function getArticle($args){
      $db = &JFactory::getDBO();

      $article_id = $args['article_id'];

      $query  = "select ";
      $query .= "con.id, con.alias, con.introtext, cat.alias as catalias, con.catid, ";
      $query .= "con.title ";
      $query .= "from #__content as con, #__categories as cat ";
      $query .= "where con.id = ".$article_id." ";
      $query .= "and con.catid = cat.id ";
      $query .= "and con.state = 1 ";

      $db->setQuery($query);
      $items = ($items = $db->loadObjectList())?$items:array();
      return $items;
    }
}
