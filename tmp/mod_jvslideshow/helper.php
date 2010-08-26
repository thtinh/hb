<?php
/**
* @version 1.5.x
* @package JoomVision Project
* @email webmaster@joomvision.com
* @copyright (C) 2008 http://www.JoomVision.com. All rights reserved.
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

$user 		= &JFactory::getUser();
$db 		= &JFactory::getDBO();
$menu 		= &JSite::getMenu();
$document	= &JFactory::getDocument();

JHTML::_('script','jd.gallery.js','modules/mod_jvslideshow/assets/js/');
JHTML::_('script','jd.gallery.transitions.js','modules/mod_jvslideshow/assets/js/');

class modJVSlideshowHelper
{

	function getList(&$params)
	{
		$config = new JConfig();
		$sitename = $config->sitename;
		//echo $sitename;

		$folder			= $params->get('folder');
		$thumb			= $params->get('thumb');
		$thumbsize		= $params->get('thumbsize');
		$intro_lenght	= $params->get('intro_lenght',200);
		$order			= $params->get('order');
		$title			= modJVSlideshowHelper::filterContent(explode("\n",$params->get('title')));
		$link			= modJVSlideshowHelper::filterLinkContent(explode("\n",$params->get('link')));
		$description	= modJVSlideshowHelper::filterContent(explode("\n",$params->get('description')));

		$images 	= array ();
		$folders 	= array ();
		$docs 		= array ();

		$fileList 	= JFolder::files($folder);
		$folderList = JFolder::folders($basePath);

		if($order == 1)
			$fileList = array_reverse($fileList);
		elseif($order == 2)
			$fileList = modJVSlideshowHelper::twodshuffle($fileList);

		// Iterate over the files if they exist
		if ($fileList !== false) {
			$i = 0;
			foreach ($fileList as $file)
			{
				if (is_file($folder.DS.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {

					$tmp = new JObject();

					$tmp->path = str_replace(DS, '/', JPath::clean($folder.DS.$file));
					if($thumb) {
						$tmp->path = modJVSlideshowHelper::getThumb($tmp->path, '_jvslideshow2', $thumbsize);
					}
					$tmp->size = filesize($tmp->path);

					$ext = strtolower(JFile::getExt($file));

					switch ($ext)
					{
						// Image
						case 'jpg':
						case 'png':
						case 'gif':
						case 'xcf':
						case 'odg':
						case 'bmp':
						case 'jpeg':
							$info				= @getimagesize($tmp->path);
							$tmp->width			= @$info[0];
							$tmp->height		= @$info[1];
							$tmp->type			= @$info[2];
							$tmp->mime			= @$info['mime'];
							$tmp->title			= ($title[$i] != "") ? strip_tags($title[$i]) : $sitename ;
							$tmp->link			= ($link[$i] != "") ? $link[$i] : JURI::base();
							$tmp->description	= ($description[$i] != "") ? modJVSlideshowHelper::introContent($description[$i],$intro_lenght) : $sitename;
							$images[] = $tmp;
							$i++;
							break;
						// Non-image document
						default:
							$docs[] = $tmp;
							break;
					}

				}
			}
		}

		return $images;
	}

	function filterContent($contents) {

		$list = array();
		$i = 0;
		foreach($contents as $content) {
			if($content) {
				$list[] = $content;
				$i++;
			}
		}

		return $list;
	}

	function filterLinkContent($contents) {

		$list = array();
		$i = 0;
		foreach($contents as $content) {
			if($content) {
				$content = str_replace("http://","",$content);
				$content = "http://".$content;
				$list[] = $content;
				$i++;
			}
		}

		return $list;
	}

	function twodshuffle($array)
	{
		// Get array length
		$count = count($array);
		// Create a range of indicies
		$indi = range(0,$count-1);
		// Randomize indicies array
		shuffle($indi);
		// Initialize new array
		$newarray = array($count);
		// Holds current index
		$i = 0;
		// Shuffle multidimensional array
		foreach ($indi as $index)
		{
			$newarray[$i] = $array[$index];
			$i++;
		}
		return $newarray;
	}

	function getImageSizes($file) {
		return getimagesize($file);
	}

	function introContent( $text, $length=200 ) {
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		$text = preg_replace( '/{.+?}/', '', $text);
		$text = strip_tags(preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text ));
		if (strlen($text) > $length) {
			$text = substr($text, 0, strpos($text, ' ', $length)) . "..." ;
		} 
		return $text;
	}

}
?>