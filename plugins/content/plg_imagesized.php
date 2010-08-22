<?php

/**

 * @version $Id: plg_imagesized.php  2009-December-20 11:41 Saigon time 

 * @ Viet Nguyen Hoang - viet4777 - viet4777.vatlieu.us - www.xahoihoctap.net & www.luyenkim.net $

 * @package     Joomla

 * @subpackage	Content

 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.

 * @license		GNU/GPL, see LICENSE.php

 * Joomla! is free software. This version may have been modified pursuant

 * to the GNU General Public License, and as distributed it includes or

 * is derivative of works licensed under the GNU General Public License or

 * other free or open source software licenses.

 * See COPYRIGHT.php for copyright notices and details.

 */

// Check to ensure this file is included in Joomla!

defined( '_JEXEC' ) or die();

jimport( 'joomla.event.plugin' );



$enabled = JPluginHelper :: isEnabled ('content','plg_imagesized');	

/**

 * Content Plugin

 *

 * @package		Joomla

 * @subpackage	Content

 * @since 		1.5

 */



class plgContentPlg_imagesized extends JPlugin

{

	/**

	 * Constructor

	 *

	 * For php4 compatability we must not use the __constructor as a constructor for plugins

	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.

	 * This causes problems with cross-referencing necessary for the observer design pattern.

	 *

	 * @param object $subject The object to observe

	 * @param object $params  The object that holds the plugin parameters

	 * @since 1.5

	 */

	function plgContentPlg_imagesized( &$subject, $params )

	{	

		parent::__construct( $subject, $params );

		$this->_plugin = & JPluginHelper::getPlugin('content','plg_imagesized');

		$this->params = & new JParameter( $this->_plugin->params );



	}



	/**

	 * prepare content method

	 *

	 * Method is called by the view

	 *

	 * @param 	object		The article object.  Note $article->text is also available

	 * @param 	object		The article params

	 * @param 	int			The 'page' number

	 */

function onPrepareContent ( &$article, &$params, $limitstart=0) //onAfterDisplayContent onPrepareContent

{

		$option		= JRequest::getCmd('option'); //Chi xuat hien voi com_content

		$view		= JRequest::getCmd('view'); //Chi xuat hien voi view = article, frontpage

		$plugin_enabled = $this->params->get('enabled','1');

		$icl_exc_catsect= $this->params->get( 'icl_exc_catsect', '1');

		$catid_list= $this->params->get( 'catid_list', 'x');

		if($catid_list == 'x') $catid_list = '';

		$sectionid_list= $this->params->get( 'sectionid_list', 'x');

		if($sectionid_list == 'x') $sectionid_list = '';

		$catid_list = explode( ',', trim($catid_list));

		$sectionid_list = explode( ',', trim($sectionid_list));

		

		$itemid_list_ = $this->params->get( 'itemid_list', 'x');

		if($itemid_list_ == 'x') $itemid_list_ = '';

		$itemid_list = explode( ',', trim($itemid_list_));

		$itemid =  @JSite::getMenu()->getActive()->id;

		

		

		global $mainframe;



		//$itemid = &JSite::getMenu()->getActive()->id;



				

			

	if(($plugin_enabled=='1') && ($option == 'com_content') ) {

	//@ -> do not show warning message

	if(($option == 'com_content') && ($sectionid_list[0] || $catid_list[0] || $itemid_list[0])) {

		$bypass = in_array(@$article->sectionid,$sectionid_list) || in_array(@$article->catid,$catid_list) || in_array($itemid,$itemid_list);

		//Excluded section

		if ($icl_exc_catsect) $bypass = !$bypass;

		if(!$bypass ) return;

	}

	//&& $option=="com_content" && $view=="article"

   		//frontpage -> $view = frontpage

   		//article -> $view = article

/******************Plugin Parameters*******************************/   

		$expire = (int) $this->params->get( 'expire', '30' ); //30 days

		$sized_img_article = (int) $this->params->get( 'sized_img_article', '1' );

		$quality = (int) $this->params->get( 'quality', '85' ); //Image Quality

		$ar_width = (int) $this->params->get( 'ar_width', '400' );

		$ar_height = (int) $this->params->get( 'ar_height', '400' );

		$ar_remove_link = (int) $this->params->get( 'ar_remove_link', '1' );

		$ar_link2originalimage = (int) $this->params->get( 'ar_link2originalimage', '1' );

		$extra_link2originalimage = trim($this->params->get( 'extra_link2originalimage', '' ));

		if($extra_link2originalimage !='') $extra_link2originalimage = ' '.$extra_link2originalimage;

		

		$ar_remove_img_tag = explode(':',$this->params->get( 'ar_remove_img_tag', 'style:class' ));

		$excluded_images = explode(':',$this->params->get( 'excluded_images',""));

		

		$only_frontpage = $this->params->get( 'only_frontpage', 'o' );

		$fp_width = (int) $this->params->get( 'fp_width', '120' );

		$fp_height = (int) $this->params->get( 'fp_height', '90' );

		$fp_width_l = (int) $this->params->get( 'fp_width_l', '240' );

		$fp_height_l= (int) $this->params->get( 'fp_height_l', '160' );

		$fp_align_l = $this->params->get( 'fp_align_l', 'left' );//left, right, center

		$fp_position_l = $this->params->get( 'fp_position_l', '0' );//left, right

		$fp_textalign_l = $this->params->get( 'fp_textalign_l', 'justify' );//justify, left, right, center

		

		$fp_image_link = (int) $this->params->get( 'fp_image_link', '1' );

		$fp_remove_class = (int) $this->params->get( 'fp_remove_class', '1' );

		$fp_default_image = $this->params->get( 'fp_default_image', '' );

		$fp_used_default_image = (int) $this->params->get( 'fp_used_default_image', '1' );



		$extra_class = $this->params->get( 'extra_class', '' );

		$fp_frcolor = $this->params->get( 'fp_frcolor', '#ffffff' ); //Frame

		$fp_bocolor = $this->params->get( 'fp_bocolor', '#a0a0a0' ); //Border

		$fp_vspace = (int) $this->params->get( 'fp_vspace', '0' );

		$fp_hspace = (int) $this->params->get( 'fp_hspace', '6' );

		$fp_align = $this->params->get( 'fp_align', 'left' );//left, right, center

		$fp_position = $this->params->get( 'fp_position', '0' );//left, right

		$fp_textalign = $this->params->get( 'fp_textalign', 'justify' );//justify, left, right, center

		$fp_chars = (int) $this->params->get( 'fp_chars', '300' );

		$fp_chars_l = (int) $this->params->get( 'fp_chars_l', '250' );

		$fp_more = $this->params->get( 'fp_more', '' );		

		$allowed_tags =  $this->params->get( '$allowed_tags', '<i><b><strong><br><p>' );

		if($allowed_tags == '-') $allowed_tags = '';

		//JPATH_SITE, JPATH_ROOT, and JPATH_BASE Đường dn của host khong phia la www.xahoihoctap.net     



                switch($only_frontpage){

			case 'f':

				$viewtype = array('f');

				break;

			case 'b':

				$viewtype = array('c','s');

				break;

			case 'n':

				$viewtype = array();

				break;

			default:

				$viewtype = array('f','c','s');

		}

                

		$plg_matches = array();



		$contentConfig = &JComponentHelper::getParams( 'com_content' );

		$leading_n = (int) $contentConfig->get( 'num_leading_articles', 0 );

		

		$current_item = $leading_n + 1;

		if(isset($article->catid) && isset($article->sectionid)){

			$mainframe->set( 'imgzised_article_num' , $mainframe->get( 'imgzised_article_num',0 )+1 );

			$current_item =  $mainframe->get( 'imgzised_article_num');

		} else  return; //Do not process for non #__content



		if($current_item <= $leading_n){

			$fp_width = $fp_width_l;

			$fp_height = $fp_height_l;

			$fp_chars = $fp_chars_l;

			$fp_align = $fp_align_l;

			$fp_position = $fp_position_l;

			$fp_textalign = $fp_textalign_l;			

		}

		

		//$plg_entrytext = 'leading:'. $leading_n.' intro:'.$intro_n.' skip:'.$skip_articles.' current:'.$current_item.' '.$article->text;

		$plg_entrytext = $article->text; 

		

	//echo $article->sectionid.' '.$article->catid.'$$$<br/>';

	$have_images = preg_match_all("|<[\s\v]*img[\s\v][^>]*>|Ui", $plg_entrytext, $plg_matches, PREG_PATTERN_ORDER) > 0;



	if (!$have_images && $fp_used_default_image && in_array($view[0],$viewtype)){

		$fp_default_img = '<img src="'.$fp_default_image.'" />';

		$plg_entrytext .= $fp_default_img;

		$plg_matches[0][] = $fp_default_img;

		$have_images = TRUE;

		//$plg_matches[0][0] = $fp_default_img;

	}

	if ($have_images)	

	{

			$imagesxxx = 0;

			//if($ar_remove_link && $view[0] == 'a') $plg_entrytext = preg_replace('@<a (.*?)>(.*?)<\\/a>@s', '$2', $plg_entrytext);

			

			$this->remove_linked_image($ar_remove_link && $view[0] == 'a', $excluded_images,$plg_entrytext);

		  foreach ($plg_matches[0] as $plg_match) 

		  {

			//echo htmlspecialchars($plg_match)."<br/>";

			//echo $option.' '.$view.'<br/>';

			//$num_articles = count($plg_matches[0]);

			// full link of image: <img src=".." ... />

			//$viewtype assigned to array('f','c','s')

			if(in_array($view[0],$viewtype)) {//frontpage, catblog, sectionblog

				$showbottext = $this->plg_images_resize2($plg_match,$fp_width,$fp_height,$quality, $fp_frcolor, $fp_bocolor, $widthm ,$heightm);

				$showbottext = $this->remove_imagelink_info($showbottext,'width',$widthm);

				$showbottext = $this->remove_imagelink_info($showbottext,'height',$heightm);

				$showbottext = $this->change_imagelink_info($showbottext,'align',$fp_align);

				$showbottext = $this->remove_imagelink_info($showbottext,'hspace',$fp_hspace);

				$showbottext = $this->remove_imagelink_info($showbottext,'vspace',$fp_vspace);

				$showbottext = $this->change_imagelink_info($showbottext,'style','width:'.$widthm.'px; '.

				'height:'.$heightm.'px; margin:'.$fp_vspace.'px '.$fp_hspace.'px;');

				$showbottext = $this->change_imagelink_info($showbottext,'border','0');

				if($fp_remove_class)$showbottext = $this->remove_imagelink_info($showbottext,'class');

				

				if($fp_image_link) {      //$article->slug, $article->catslug, $article->sectionid

					$link   = JRoute::_(ContentHelperRoute::getArticleRoute(@$article->slug, @$article->catslug, @$article->sectionid.":testset"));

					$showbottext = '<a href="'.$link.'">'.$showbottext.'</a>';

				}

				$imagesxxx = $imagesxxx + 1;

				if($imagesxxx == 1) $showbottext2 = $showbottext;

				if($imagesxxx > 1) $showbottext = '';

 

			}

			elseif($view[0] == 'a' && $sized_img_article) {//Article

				$showbottext = $this->plg_images_resize($plg_match,$ar_width,$ar_height,$quality, $widthm ,$heightm, $originallink);

				$showbottext = $this->change_imagelink_info($showbottext,'width',$widthm);

				$showbottext = $this->change_imagelink_info($showbottext,'height',$heightm);

				if($ar_remove_img_tag)

				foreach($ar_remove_img_tag as $tag ) $showbottext = $this->remove_imagelink_info($showbottext,$tag);

			} else return ;



			//echo "$$$$".htmlspecialchars($showbottext)."<br/>";

			//$originallink

			if($ar_link2originalimage && $view[0] == 'a'){

						//$findme   = 'download.gif';

						$pos = false;

						if($excluded_images) 

							foreach ($excluded_images as $findme) 

								if($findme) $pos = $pos || strpos($originallink, $findme);

						//$pos = strpos((string)$originallink, $findme);

						if ($pos === false) {

							$plg_entrytext = str_replace( $plg_match, '<a href="'.$originallink.'"'.$extra_link2originalimage.' target="_blank">'.$plg_match.'</a>', $plg_entrytext);

						}

					//$plg_entrytext = str_replace( $plg_match, '<a href="'.$originallink.'"" target="_blank">'.$plg_match.'</a>', $plg_entrytext);

					}

			$plg_entrytext = str_replace( $plg_match, $showbottext, $plg_entrytext);	

		  }

		unset($plg_match);

		  $article->text = $plg_entrytext;

			if($view[0] == 'a') {

			 	$article->text = $plg_entrytext;			

			}  

		 	//Frontpage or Cat.- Sect.-Blogs

			else {

			if($fp_textalign == 'none') $article->text = $plg_entrytext;

			else

			switch ($fp_position) {

			    case 0:

			        $article->text = '<div style="text-align: '.$fp_textalign.'; '.$extra_class.'">' . $showbottext2 . $this->chars($plg_entrytext,$fp_chars,$fp_more,$allowed_tags).'</div>';

			        break;

			    case 1: //above

			        $article->text = $showbottext2 .'<br />'.'<div style="text-align: '.$fp_textalign.'; '.$extra_class.'">'.$this->chars($plg_entrytext,$fp_chars,$fp_more,$allowed_tags).'</div>';

			        break;

			    case 2: //bellow

			        $article->text = '<div style="text-align: '.$fp_textalign.'; '.$extra_class.'">'.$this->chars($plg_entrytext,$fp_chars,$fp_more,$allowed_tags).'</div>'.'<br />'.$showbottext2 ;

			        break;

			}		     

			} 

	}

                 $this->clean_cache($expire);

	}

}// End Function

/******************************************/

function plg_images_resize($text, $i_width=100,$i_height=100,$quality, & $widthm, & $heightm, & $originallink) 

{

	global $mainframe;

	$baseurl = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();

	$cache_path =JPATH_SITE."/plugins/content/imagesresizecache";

	$cache_path_http = "plugins/content/imagesresizecache"; //$baseurl.



	if (preg_match_all("|src[\s\v]*=[\s\v]*['\"](.*)['\"]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0)

	{

		if (preg_match_all("|src[\s\v]*=[\s\v]*([\S]+)[\s\v]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0) return $text;

	}

	 

	$src = $matches[1][0];

	$originallink = $src;

	$width = $widthm = $i_width; 

	$height = $heightm = $i_height;



	$hash = md5($src.$quality.$width.$height);



	$filename = $hash.".jpeg";

	$full_path_filename = $cache_path."/".$filename;



	if (@is_file($full_path_filename)) 

	{//Anh tai local

	touch($full_path_filename);

	$url = $cache_path_http."/".$filename;

	list($widthm, $heightm) = getimagesize($url);

	} 

	else

	 {//Anh cbua xu ly

	$image = $this->getimagedata($src);

	if ($image == false) $image = $this->getimagedata($baseurl."/".$src);

	if ($image == false) return $text;

	$width_orig = imagesx($image);

	$height_orig = imagesy($image);

	if($width_orig <= $i_width && $height_orig <= $i_height) {$widthm = $width_orig;$heightm =$height_orig; return $text;}

	$ratio_orig = $width_orig/$height_orig;

	if ($width/$height > $ratio_orig) {

	   $width = $widthm = floor($height*$ratio_orig);//round

	} else {

	   $height = $heightm = floor($width/$ratio_orig);//round

	}

	$result = @imagecreatetruecolor($width, $height);

	if ($result == false) return $text;

	$sample = @imagecopyresampled($result, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

	if ($sample == false) return $text; 

	$save = @imagejpeg($result, $full_path_filename, $quality);



	if ($save == false) return $text;

	@imagedestroy($image);

	@imagedestroy($result);

	$url = $cache_path_http."/".$filename;

	}

	$text = str_replace($src, $url, $text);

	return $text;

} 







function plg_images_resize2($text, $i_width=100,$i_height=100,$quality, $fp_frcolor, $fp_bocolor, & $widthm, & $heightm) 

{

	global $mainframe;

	$baseurl = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();

	$cache_path =JPATH_SITE."/plugins/content/imagesresizecache";

	$cache_path_http = "plugins/content/imagesresizecache"; //$baseurl.



	if (preg_match_all("|src[\s\v]*=[\s\v]*['\"](.*)['\"]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0)

	{

		if (preg_match_all("|src[\s\v]*=[\s\v]*([\S]+)[\s\v]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0) return $text;

	}

	 

	$src = $matches[1][0];

	$width = $widthm = $i_width; 

	$height = $heightm = $i_height;



	$hash = md5($src.$quality.$width.$height);



	$filename = $hash.".jpeg";

	$full_path_filename = $cache_path."/".$filename;



	if (@is_file($full_path_filename)) 

	{

	touch($full_path_filename);

	$url = $cache_path_http."/".$filename;

	list($widthm, $heightm) = getimagesize($url);

	} 

	else

	 {

	// Get new dimensions

	$image = $this->getimagedata($src);

	if ($image == false) $image = $this->getimagedata($baseurl."/".$src);

	if ($image == false) return $text;

	$width_orig = imagesx($image);

	$height_orig = imagesy($image);

	$ratio_orig = $width_orig/$height_orig;

	if ($width/$height > $ratio_orig) {

	   $width = $height*$ratio_orig;

	} else {

	   $height = $width/$ratio_orig;

	}



	// Resample

	$result = imagecreatetruecolor($widthm , $heightm);

	imagefill($result , 0,0 , $this->set_img_color($result,$fp_frcolor));

	//draw outer border

	imagerectangle($result, 0, 0, $widthm-1, $heightm-1, $this->set_img_color($result,$fp_bocolor));



	$sample = imagecopyresampled($result, $image, -($width/2) + ($widthm/2)+2, -($height/2) + ($heightm/2)+2, 0, 0, $width-4, $height-4, $width_orig, $height_orig);

	//$sample = true;

	if ($sample == false) return $text; 

	$save = @imagejpeg($result, $full_path_filename, $quality);



	if ($save == false) return $text;

	@imagedestroy($image);

	@imagedestroy($result);

	$url = $cache_path_http."/".$filename;

	}

	$text = str_replace($src, $url, $text);

	return $text;

}





function getimagedata($file)

{

	$data = @file_get_contents($file);

	if ($data == false) return false;

	return @imagecreatefromstring($data);

}



function set_img_color(&$image, $text_color='#ffffff'){

	$text_color = strtolower($text_color);

	$red = 255;

	$green = 255;

	$blue = 255;

	if( eregi( "[#]?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})", $text_color, $ret ) )

	{

	    $red = hexdec( $ret[1] );

	    $green = hexdec( $ret[2] );

	    $blue = hexdec( $ret[3] );

	}

	return imagecolorallocate($image, $red, $green, $blue);

}



function get_title_alt_imagelink_info($text) {

	if (preg_match_all("|title[\s\v]*=[\s\v]*['\"](.*)['\"]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0)

	{

		if (preg_match_all("|title[\s\v]*=[\s\v]*([\S]+)[\s\v]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0) return array('','');

	}

	$title = $matches[1][0];

	if (preg_match_all("|alt[\s\v]*=[\s\v]*['\"](.*)['\"]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0)

	{

		if (preg_match_all("|alt[\s\v]*=[\s\v]*([\S]+)[\s\v]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0) return array($title,'');

	}

	$alt = $matches[1][0];

	return array($title,$alt);

}

/*

function new_imagelink_info($text, $width=150, $height=150, $align='left', $title='', $alt='', $border = 0, $vspace = 0, $hspace = 6) {

	if (preg_match_all("|src[\s\v]*=[\s\v]*['\"](.*)['\"]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0)

	{

		if (preg_match_all("|src[\s\v]*=[\s\v]*([\S]+)[\s\v]|Ui",$text,$matches,PREG_PATTERN_ORDER) == 0) return $text;

	}

	 

	$src = $matches[1][0];



	$text = '<img src="'.$src.'" ';

	$text .= ' width="'.$width.'"';

	$text .= ' height="'.$height.'"';

	$text .= ' align="'.$align.'"';

	$text .= ' title="'.$title.'"';

	$text .= ' alt="'.$alt.'"';

	$text .= ' border="'.$border.'"';

	$text .= ' hspace="'.$hspace.'"';

	$text .= ' vspace="'.$vspace.'"';

	$text .= ' />';

	return $text;

}

*/

function remove_imagelink_info($text, $name) {

	return preg_replace("|".$name."[\s\v]*=[\s\v]*['\"](.*)['\"]|Ui",'',$text,1);

}

function change_imagelink_info($text, $name, $value) {

	//If nout found, add it

	if (strpos($text,$name)=== false) return str_replace('/>',' '.$name.'="'.$value.'"'.' />',$text);

	//If found, changes it new value

	return preg_replace("|".$name."[\s\v]*=[\s\v]*['\"](.*)['\"]|Ui",$name.'="'.$value.'"',$text,1);

}



function chars($text_, $charnum, $more='', $allowed_tags =  "<i><b><strong><br><p>"){

	if($charnum == -1) return '';

	$strip = strip_tags($text_);

	if(JString::strlen($strip) > $charnum){

	$lasttext = ($more=='')? " [&nbsp;&hellip;&nbsp;]": $more;

	}

	else $lasttext = '';

	

	$strip = JString::substr($strip, 0, $charnum);

	$striptag = strip_tags($text_, $allowed_tags);

	$lentag = JString::strlen($striptag);	

	$printtext = "";

	

	$x = 0;

	$ignore = true;

	for($n = 0; $n < $charnum; $n++) {

		for($m = $x; $m < $lentag; $m++) {

			$x++;

			if(JString::substr($striptag,$m,1) == "<") {

				$ignore = false;

			} else if(JString::substr($striptag,$m,1) == ">") {

				$ignore = true;

			}

			if($ignore == true) {

				if(JString::substr($strip,$n,1) != JString::substr($striptag,$m,1)) {

					$printtext .= JString::substr($striptag,$m,1);

				} else {

					$printtext .= JString::substr($strip,$n,1);

					break;

				}

			} else {

				$printtext .= JString::substr($striptag,$m,1);

			}

		}

	}

return $this->closetags($printtext.$lasttext);

}

function closetags ( $html )

{

    $arr_single_tags = array('meta','img','br','link','area');

    #put all opened tags into an array

    preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );

    $openedtags = $result[1];



    #put all closed tags into an array

    preg_match_all ( "#</([a-z]+)>#iU", $html, $result );

    $closedtags = $result[1];

    $len_opened = count ( $openedtags );

    # all tags are closed

    if( count ( $closedtags ) == $len_opened )

    {

        return $html;

    }

    $openedtags = array_reverse ( $openedtags );

    # close tags

    for( $i = 0; $i < $len_opened; $i++ )      

    {

        if ( !in_array ( $openedtags[$i], $closedtags ) )

        {

            if(!in_array ( $openedtags[$i], $arr_single_tags )) $html .= "</" . $openedtags[$i] . ">";

        }

        else

        {

            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );

        }

    }

    return $html;

}

/*

function closetags($html)

{

	$arr_single_tags = array('meta','img','br','link','area');

	

	preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);

	$openedtags = $result[1];

	

	preg_match_all('#</([a-z]+)>#iU', $html, $result);

	$closedtags = $result[1];

	

	$len_opened = count($openedtags);

	

	if (count($closedtags) == $len_opened)

	{

	return $html;

	}

	$openedtags = array_reverse($openedtags);

	

	for ($i=0; $i < $len_opened; $i++)

	{

		if (!in_array($openedtags[$i],$arr_single_tags))

		{

			if (!in_array($openedtags[$i], $closedtags))

			{

				if ($next_tag = $openedtags[$i+1])

				{

					//$html = preg_replace('#</'.$next_tag.'#iU','</'.$openedtags[$i].'></'.$next_tag,$html);

					$tmp_html = $html;

					$html = preg_replace('#</'.$next_tag.'#iU','</'.$openedtags[$i].'></'.$next_tag,$html);

					if($html == $tmp_html){ //if it did not replace, do it now

						$html .= '</'.$openedtags[$i].'>';

					}					

				}

				else

				{

					$html .= '</'.$openedtags[$i].'>';

				}

			}

		}

	}

return $html;



}

*/

function clean_cache($expire) 

{

	 $cache_path =JPATH_SITE."/plugins/content/imagesresizecache";



	 if ($expire <= 0) return;



	 if (@is_file($cache_path."/lastclean"))

	 {

	$t = filemtime($cache_path."/lastclean");

	if (date("dmY") == date("dmY", $t)) return;

	 }

	 

	 $d = date("z") + 365*date("Y");

	 

	 if ($dh = @opendir($cache_path))

	 {

	while (($file = readdir($dh)) !== false) 

	{

	 if (substr($file, -5) == ".jpeg")

	 {

	$t = filemtime($cache_path."/".$file);

	  if ($d - date("z", $t) - 365*date("Y", $t) >= $expire) @unlink($cache_path."/".$file);

	   }

	  }

	  @closedir($dh);

	 } 



	 touch($cache_path."/lastclean");

} 



function remove_linked_image($ar_remove_link, & $excluded_images, & $text){

	if($ar_remove_link) {

		if(preg_match_all("@<a (.*?)>(.*?)<\\/a>@s", $text,

		$matches, PREG_PATTERN_ORDER))

		foreach ($matches as $match) 

			if(preg_match("|<[\s\v]*img[\s\v][^>]*>|Ui",(string)$match,$matched)) {

			//$findme   = 'download.gif';

			$pos = false;

			if($excluded_images) foreach ($excluded_images as $findme) 

				if($findme) $pos = $pos || strpos((string)$matched, $findme);

			if ($pos === false) {

				$text = str_replace($match, $matched, $text);

			}			

			}

		unset($match);

	}

}



} // End Class

?>





