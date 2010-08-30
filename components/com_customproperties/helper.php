<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.7 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Shows associated custom properties
* @param cpContenElement $ce cp Content Element Object
* @param cid = content_id to show the customproperties
* @param object $params params object
* @param bool $append_to_meta appends tags to meta keywords. Default false. Note: this function makes sense when only one article is displayed (cplugin). There's no point in appending the tags of the search result page, to meta.
* @returns string html formatted cp_tags areal
*/
function showTags($ce, $cid, $params, $append_to_meta=false){

	global $Itemid;

	$database 		= JFactory::getDBO();
	$user 			= JFactory::getUser();
	$aid			= $user->get('aid',0);
	$result			= "";
	$tagstring		= "";
	$tagstrings		= array();

	$show_tag_name 	= $params->get('show_tag_name', '0');
	$linked_tags 	= $params->get('linked_tags', '0');
	$url_format 	= $params->get('url_format'	, '0');
	$use_itemid		= $params->get('use_itemid', '1');

	$query = "SELECT DISTINCT f.id as fid, f.label as name, v.id as vid, v.label
		FROM #__custom_properties AS cp
			INNER JOIN #__custom_properties_fields AS f
			ON (cp.field_id = f.id )
			INNER JOIN #__custom_properties_values AS v
			ON (cp.value_id = v.id )
		WHERE cp.ref_table = '".$ce->table."'
			AND cp.content_id = '$cid'
			AND f.published = '1'
			AND f.access <= '$aid'
		ORDER BY f.ordering, v.ordering ";

	$database->setQuery($query);
	$database->getErrorMsg();
	$tags = $database->loadObjectList();

	$result .= "<div class=\"cp_tags\">\n";
	$result .= "<span class=\"cp_tag_label\">". JText::_('Tags').": </span>";

	$itemid_url = "";
	if($use_itemid == 1){
		$itemid_url = "&Itemid=".$Itemid ;
	}


	$tags_count = 0;
	foreach($tags as $tag){
		$tagstring = "";

		if($url_format == 0){
			$link = JRoute::_("index.php?option=com_customproperties&task=tag&tagId=". $tag->vid .$itemid_url);
		}
		else{
		  	$link = JRoute::_("index.php?option=com_customproperties&task=tag&tagName=". urlencode($tag->name.":".$tag->label) . $itemid_url);
		}

		$result .= "<span class=\"cp_tag cp_tag_".$tag->vid."\">";
		if($linked_tags) $result .= "<a href=\"$link\">";

		if($show_tag_name) $tagstring = htmlspecialchars($tag->name) . ": ";
		$tagstring .= htmlspecialchars($tag->label);
		$result .= $tagstring;

		if($linked_tags) $result .= "</a>\n";
		$result .= "</span> ";

		$tagstrings[] = $tagstring;

		$tags_count++;
	}

	$fe_tagging = false;
	if($params->get('frontend_tagging') == 1 && ($aid >= $params->get('editing_level'))){
		$fe_tagging = true;
		JHTML::_( 'behavior.modal' );
		$link = JRoute::_('index.php?option=com_customproperties&controller=tagging&view=tagging&tmpl=component&id='.$cid);
		$rel = "{handler: 'iframe', size: {x: 570, y: 400}}";
		$result .= "<span class=\"cp_tag\"><a class=\"modal\" rel=\"$rel\" href=\"$link\">".JText::_('Edit tags')."</a></span>\n";
	}


	$result .= "</div>\n";

	if($append_to_meta && count($tagstrings)){
		$document	= JFactory::getDocument();
		$keywords 	= $document->getMetaData('keywords');
		$document->setMetaData('keywords', $keywords.','. join(', ', $tagstrings) );
	}
	// dont't show the div at all if no tags are found , and frontend taggin is not enabled'

	if($fe_tagging == false && $tags_count == 0){
		$result = "";
	}

	return $result;
}

/**
* This function is taken from mosPrepareSearchContent:
* @package Joomla
* @subpackage Search
* file : joomla.php
* Modified by : Andrea Forghieri
* Solidsystem
*/
function prepareContent($text, $length=200){
  // strips tags won't remove the actual jscript
  $text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
  $text = preg_replace( '/{.+?}/', '', $text);

  // replace line breaking tags with whitespace
  $text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );
  $text = strip_tags($text);
  return substr( $text, 0, $length) . (strlen($text) > $length ? '...' : '');
}


/** This functions strips images from HTML and puts them
 * into the 'images' field
 * @param row
 * @param strin $image_path Images path
 * @returns row
 */
function stripImages(&$row, $image_path){

	if(empty($row)){return ;}

	preg_match("/<img[^>]*>/i", $row->introtext . $row->fulltext, $matches);
	if (!empty($matches))
	{
		foreach ($matches as $txtimg)
		{
			/* strip the img, gonna append later */
			$row->introtext = str_replace($txtimg,"",$row->introtext);
			$txtimg 		= urldecode($txtimg); // in case it's an URL

			if (strpos($txtimg, "http")!== false) {
				// image is remote
				if(preg_match('#src=[\"\']{1}(.*?)["\']#i',$txtimg,$imgsrcs) ){
					if (!empty($row->images)) {
						$row->images = $imgsrcs[1] . "\n" . $row->images;
					}
					else {
						$row->images = $imgsrcs[1];
					}

				}

			}
			elseif ( strpos($txtimg, $image_path) !== false ){
				// img is local
				if (strpos($txtimg, 'src="/') !== false) {
					preg_match("#src=\"\/" . addslashes($image_path) . "\/([\ \:\-\/\_A-Za-z0-9\.]+)\"#i",$txtimg,$imgsrcs);
				}
				else {
					preg_match("#src=\"" . addslashes($image_path) . "\/([\ \:\-\/\_A-Za-z0-9\.]+)\"#i",$txtimg,$imgsrcs);
				}

				if ($row->images != "") {
					$row->images =  $row->images ."\n" . $imgsrcs[1] ;
				}
				else {
					$row->images = $imgsrcs[1];
				}
			}
		}
	}

	/* let's strip al mos tags */
	$row->introtext= preg_replace("/{[^}]*}/","",$row->introtext);
	return $row;
}

/** This function calculate the new dimension of an
 * image so that it fits inside a given box
 * @param $srcx , $srcy size of the image to be resized
 * @param $forcewidth, $forcheight size of the resize box
 * @param bool $aspect true / false, keep aspect
 * */
function calcThumbSize($srcx, $srcy, &$forcedwidth, &$forcedheight, $aspect)
{
	//TODO check this function (calc thumb size)
	$img_aspect = $srcx / $srcy > 1 ? "portrait" : "landscape";
	$box_aspect = $forcedwidth / $forcedheight > 1 ? "portrait" : "landscape";

	if($img_aspect == $box_aspect){
		$target = max($forcedheight , $forcedwidth);
	}
	else{
		$target = min($forcedheight , $forcedwidth);
	}

	if($srcx > $srcy){
		$ratio = $target / $srcx ;
	}
	else{
		$ratio = $target / $srcy ;
	}
	$resize = ( $srcx > $forcedwidth || $srcy > $forcedheight) ? true : false ;
	if($resize && $aspect){
		$forcedwidth = round($srcx * $ratio);
		$forcedheight = round($srcy * $ratio);
	}
}

/** This function returns the thumbnail preview.
 * If it does not exists or if it is deemed cont
 * current, a new one is generated.
 * @param $file image filename (without path)
 * @param $image_dir images directory respective of site url e.g. images/stories , no trailing slash
 * @param $width, $heigth size of the thumbnail
 * @param $extra extra attributes to be given to the returned HTML tag (class, title , alt)
 * @param $aspect true, false keep aspect ratio
 * @param integer $quality quality of the image (0 worst - 100 best)
 * @param string $thumb_dir directory to save the thumbnails into. Default: component/images
 * @return HTML tag or false if errors occurred
 */

function getThumb($file, $image_dir,  $width, $heigth, $extra="", $aspect=true, $quality = '75', $thumb_dir=null, $debug=false)
{
	$found = false;
	$is_URL = false;

	if(empty($file)){return false;}
	if(empty($image_dir)){return false;}

	if(empty($thumb_dir)){$thumb_dir = 'components'.DS.'com_customproperties'.DS.'images';}

	// define some directories
	$dst_dir = preg_replace("#^(http:)?//?#", "", $file);

	$thumb_subdir		= preg_replace('/^\./','',dirname($dst_dir));
	$images_base_path	= JPATH_ROOT . DS. $image_dir;

	if(strpos($file, 'http') === 0){
		$image_path 	= $file;
		$is_URL			= true;
	}
	else{
		$image_path 	= $images_base_path . DS . $file;
	}
	$thumb_base_path	= JPATH_ROOT. DS .$thumb_dir. DS . $thumb_subdir;
	$thumb_base_url		= str_replace( DS, '/', $thumb_dir .'/'. $thumb_subdir);
	$ext 				= substr(strrchr(basename($file), '.'), 1);

	/* make sure directory exists, otherwise create it */
	if(!is_writable( $thumb_base_path )){
		if(! mkdir($thumb_base_path, 0755, true) ){
			if($debug) 	return "<span class=\"alert\"> Can't generate thumbnails. Can't write to directory </span>";
			return false;
		}
	}

	$thumb_name	= str_replace('.'.$ext, '_thumb.'.$ext, basename($file));
	$thumb_path = $thumb_base_path.DS.$thumb_name;
	$thumb_url	= str_replace('//', '/', $thumb_base_url .'/'.$thumb_name);

	$image = '';

	if (file_exists($thumb_path))
	{
		// compare found thumb size with teorethical size, for chaching purposes
		$thumb_size = '';
		$twx = $thy = 0;
		if (function_exists( 'getimagesize' ))
		{
			$thumb_size = getimagesize( $thumb_path );
			if (is_array( $thumb_size ))
			{
				$twx = $thumb_size[0];
				$thy = $thumb_size[1];
				$size = 'width="'.$twx.'" height="'.$thy.'"';
			}

			$image_size = '';
			$wx = $hy = 0;
			$image_path = escapeFileName($image_path);
			$image_size = getimagesize( $image_path );
			if (is_array( $image_size ))
			{
				$wx = $image_size[0];
				$hy = $image_size[1];
			}
		}
		calcThumbSize($wx, $hy, $width, $heigth, $aspect);

		if($twx == $width && $thy == $heigth){
			$found = true;
			$size = 'width="'.$width.'" height="'.$heigth.'"';
			$image= '<img src="'.$thumb_url.'" '.$size.' '.$extra.'/>';
		}
	}

	if (!$found)
	{
		// make the thumbnails
		switch (strtolower($ext))
		{
			case 'jpg':
			case 'jpeg':
			case 'png':
				if(!cp_thumbit($image_path, $thumb_path, $ext, $width, $heigth, $aspect, $quality)){
					$image = "";
					if($debug) $image = "<span class=\"alert\"> Thumbnail creation failed. Missing source image $image_path.</span>";
					break;
				}
				$size = 'width="'.$width.'" height="'.$heigth.'"';
				$image= '<img src="'.$thumb_url.'" '.$size.' '.$extra.'/>';
				break;

			case 'gif':
				if (function_exists("imagegif")) {
					if(!cp_thumbit($image_path, $thumb_path, $ext, $width, $heigth, $aspect, $quality)){
						$image = "";
						if($debug) $image = "<span class=\"alert\"> Thumbnail creation failed. Missing source image $image_path.</span>";
						break;
					}
					$size = 'width="'.$width.'" height="'.$heigth.'"';
					$image= '<img src="'.$thumb_url.'" '.$size.' '.$extra.'/>';
					break;
        		}
        		else{
        			$image = "";
					if($debug) $image = "<span class=\"alert\"> Can't generate thumbnails from gif, missing function.</span>";
        			break;
        		}

			default:
        		$image = "";
				if($debug) $image = "<span class=\"alert\"> Image file type (". htmlspecialchars($ext).") not supported.</span>";
        		break;
		}
	}
	return $image;
}

/** Function taken from Mini Front Page
 * Modified by Andrea Forghieri - Solidsystem
 *
 *
 * This function creates a thumbnail preview from an image
 * @param string $file source file (complete with path)
 * @param string $thumb filename to be given to the thumb (complete with path)
 * @param string $ext Extension of the source file
 * @param int &$newheight height of the thumbnail
 * @param int &$newwidth width of the thumbnail
 * @param bool aspect preserve aspect (true / false)
 * @param integer $quality quality of the image (0 worst - 100 best)
 * @return boolean return false if
 */

function cp_thumbIt ($file, $thumb, $ext, &$new_width, &$new_height, $aspect, $quality)
{

	$filename = escapeFileName($file);
	$img_info = getimagesize($file);
	if(!$img_info){return false;}
	$orig_width = $img_info[0];
	$orig_height = $img_info[1];

	calcThumbSize($orig_width, $orig_height, $new_width, $new_height, $aspect);

	switch (strtolower($ext)) {
		case 'jpg':
		case 'jpeg':
			$im  = imagecreatefromjpeg($file);
			$tim = imagecreatetruecolor ($new_width, $new_height);
			cp_ImageCopyResampleBicubic($tim, $im, 0,0,0,0, $new_width, $new_height, $orig_width, $orig_height);
			imagedestroy($im);
			imagejpeg($tim, rawurldecode($thumb), $quality );
			imagedestroy($tim);
			break;

		case 'png':
			$im  = imagecreatefrompng($file);
			$tim = imagecreatetruecolor ($new_width, $new_height);
			cp_ImageCopyResampleBicubic($tim, $im, 0,0,0,0, $new_width, $new_height, $orig_width, $orig_height);
			imagedestroy($im);
			imagepng($tim, rawurldecode($thumb));
			imagedestroy($tim);
			break;

		case 'gif':
			if (function_exists("imagegif")) {
				$im  = imagecreatefromgif($file);
				$tim = imagecreatetruecolor ($new_width, $new_height);
				cp_ImageCopyResampleBicubic($tim, $im, 0,0,0,0, $new_width, $new_height, $orig_width, $orig_height);
				imagedestroy($im);
				imagegif($tim, rawurldecode($thumb));
				imagedestroy($tim);
			}
			break;

		default:
		break;
	}
	return true;
}

/** function copied verbatim from mod_minifrontpage
 * Template plaza
 */
function cp_ImageCopyResampleBicubic (&$dst_img, &$src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
{
	if ($dst_w==$src_w && $dst_h==$src_h) {
		$dst_img = $src_img;
		return;
	}
	ImagePaletteCopy ($dst_img, $src_img);
	$rX = $src_w / $dst_w;
	$rY = $src_h / $dst_h;
	$w = 0;
	for ($y = $dst_y; $y < $dst_h; $y++)
	{
		$ow = $w; $w = round(($y + 1) * $rY);
		$t = 0;
		for ($x = $dst_x; $x < $dst_w; $x++)
		{
			$r = $g = $b = 0; $a = 0;
			$ot = $t; $t = round(($x + 1) * $rX);
			for ($u = 0; $u < ($w - $ow); $u++)
			{
				for ($p = 0; $p < ($t - $ot); $p++)
				{
					$c = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $ot + $p, $ow + $u));
					$r += $c['red'];
      				$g += $c['green'];
      				$b += $c['blue'];
      				$a++;
    			}
			}
			if(!$a) $a = 1;
			ImageSetPixel ($dst_img, $x, $y, ImageColorClosest ($dst_img, $r / $a, $g / $a, $b / $a));
		}
	}
}
/**
 * function to escape the path of an URL
 * @return string escaped url , of original file if the passed variable is not an url
 */
function escapeFileName($file){

	if(strpos($file, 'http') === 0){
		$theURL = parse_url($file);
		$thePath = split('/', $theURL['path']);

		$theEscapedPath = join('/', array_map('rawurlencode', $thePath) );
		$file = $theURL['scheme'] . '://' . $theURL['host']. '/'. $theEscapedPath;
	}

	return $file;
}