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
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Search Result View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewShow extends JView
{
		/**
		 * Show matching records returned from search
		 * @return void
		 **/
		function display($tpl = null)
		{
			$model			= $this->getModel('search');
			$params			= $model->getParams();
			$use_joomfish 	= $params->get('use_joomfish');

			$tagId = JRequest::getInt('tagId') ;
			if($tagName = stripslashes(urldecode(JRequest::getVar('tagName')))){
				$tagId = getTagByName($tagName, $use_joomfish);
			}

			$this->assignRef( 'data',		$model->getData($tagId));

			$errors = $model->getErrors();
			$this->assignRef( 'errors',			$errors);

			// search summary and pagination make sense only if there's something to show
			if(empty($errors)){

				$this->assignRef( 'pagination',	 	$model->getPagination($tagId));

				if($params->get('show_result_summary')){
					$this->assignRef( 'result_summary', $model->getResultSummary($tagId));
				}
			}

			$this->assignRef( 'search_pars',	$model->getSearchPars($tagId));
			$this->assignRef( 'tagname',		$model->getTagName());
			$this->assignRef( 'ordering',		$model->_ordering);
			$this->assignRef( 'params',			$params);

			parent::display($tpl);
		}
}
/**
* Method to show a formatted error
*
* @param	string	the error "code"
* @returns html formatted error
*/
function searchError($error_message){

	switch($error_message){
		case "no auth":
			return "<span class=\"info\">".JText::_('CP_ERR_NOAUTH')."</span>\n";
			break;
		case "no pars":
			return "<span class=\"info\">".JText::_('CP_ERR_NOPAR')."</span>\n";
			break;
		case "text short":
			return "<span class=\"info\">".JText::_('CP_ERR_TEXTSHORT')."</span>\n";
			break;
		case "no match":
			return "<span class=\"info\">".JText::_('CP_ERR_MATCH')."</span>\n";
			break;
		case "db error":
		default :
			return "<span class=\"alert\">".JText::_('CP_ERROR')."</span>\n";
	}
}
/** This functions renders the elements of the search summary
 * @param integer $element_type 0= nothing, 1=keyword, 2=matching results, 3=tag, 4=ordering
 * @param array $list list for ordering elements
 * @param string $tagname name of the tag
 * @param string $keyword searched keyword
 * @param integer $total total number of matching results
 * @return string HTML formatted string with rendering of the element
 */
function renderSearchSummaryElement($element_type, $searchword , $tagname, $total , $list ){

	switch($element_type){
		case 1 :
			$result = JText::_( 'Search Keyword' ) .": " . ($searchword != "" ? "<b>".htmlspecialchars($searchword)."</b>" : "");
			break;
		case 2:
			$result = JText::sprintf( 'TOTALRESULTSFOUND', $total );
			break;
		case 3:
			$result = $tagname != "" ? JText::_('Tag').": <span class=\"cp_tag\">".htmlspecialchars($tagname)."</span>" :  "&nbsp;" ;
			break;
		case 4:
			$result = JText::_( 'Ordering' ) . $list;
			break;
		case 0 :
		default :
			$result = "";
			break;
	}

	return $result;
}

/**
 * Show Content Item Title
 * @param object $row Content Item row
 * @param object $params Component parameters
 * @return HTML HTML string with Article title rendered
 */
function showTitle( $row, $params ){

  $show_section = $params->get('show_section');
?>
      <div class="cp_title">
        <a href="<?php echo JRoute::_($row->href); ?>"><?php echo $row->title ?></a>
      </div>
      <?php if ( $row->section  && $show_section ){ ?>
        <span class="cp_section">
          (<?php echo $row->section; ?>)
        </span>
        <?php
      }
}
/**
 * Function to show the intro of the article
 * @param object $row Content Item row
 * @param string $searchword searched word to be highlighted
 * @param object $params Component parameters
 * @return HTML HTML string with Article title rendered
 */
function showIntro( $row, $searchword, $params ){

  $text_length = $params->get('text_length');
  $show_section = $params->get('show_section');
?>
      <div class="cp_text">
        <?php if($searchword == ""){
            echo prepareContent($row->introtext.' '.$row->fulltext, $text_length) ;
          }
          else{
            /* using same helper as mod_cpsearch */
            $helper_path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php';
            require_once($helper_path);
            $text = SearchHelper::prepareSearchContent($row->introtext.' '.$row->fulltext, $text_length, $searchword);
            $text = eregi_replace( '('.$searchword.')', '<span class="highlight">\0</span>', $text );
            echo $text;
          }
        ?>
      </div>
<?php
}
/**
 * Function to render the result as full HTML intro
 * @param object $ce content element object
 * @param object $row Content Item row
 * @param string $searchword searched word to be highlighted
 * @param object $params Component parameters
 * @return HTML HTML string with Article title rendered
 */
function showFullIntro( $ce, $row, $searchword, $params ){

	$allowed_tags 	= $params->get('allowed_tags');
	$debug 			= $params->get('debug');
	$img 			= "";
	$img_div 		= "";

	echo "<div class=\"cp_text\">\n";
	if($params->get('image_thumbnail') == 1 && $ce->images) {
		$thumb_width 	= $params->get('thumb_width');
		$thumb_height 	= $params->get('thumb_height');
		$aspect 		= $params->get('keep_aspect');
		$quality 		= $params->get('image_quality');

		$row =& stripImages($row, $ce->images_dir);
		if(!empty($row->images)){
			$title = htmlspecialchars($row->title);
			$image = strtok($row->images,"|\r\n");
			$extra = "alt=\"$title\" title=\"$title\" class=\"cp_image\"";
			$img_div = "";
			if($img =  getThumb($image, $ce->images_dir, $thumb_width, $thumb_height, $extra, $aspect, $quality, null, $debug) ){
				$img_div  = "<div class=\"cp_image\" style=\"width : ".$thumb_width."px; height : ".$thumb_height."px;\">";
				$img_div .= "<a href=\"" . $row->href . "\" alt=\"$title\">";
				$img_div .= $img;
				$img_div .= "</a>";
				$img_div .= "</div>\n";
			}
		}
	}

	$text = strip_tags($row->introtext, $allowed_tags );
	/* highlight searchword */
	if($searchword != ""){
		$text = eregi_replace( '('.$searchword.')', '<span class="highlight">\0</span>', $text );
	}
	echo $img_div . $text;

	echo "</div>\n";
}

/** returns the tagID given the tag name
* In order for this function to work corretly , the tag name must be
* made of fieldname:fieldvalue
* @param string $tagName tag name (fieldlabel:fieldlabel)
* @param boolean $use_joomfish use Joomafish compatible queries
* @return tagID or false id no meatching tg is found
 */
function getTagByName($tagName, $use_joomfish = 0){

	global $mainframe;
	$database 		= JFactory::getDBO();
	$user 			= JFactory::getUser();
	$aid			= $user->get('aid', 0);
	$language 		= $mainframe->getCfg('language');

	if(strpos($tagName,':') === false)
		return false;

	$theTag = split(':', $tagName);
	$fieldName = $database->getEscaped($theTag[0]);
	$fieldValue = $database->getEscaped($theTag[1]);
	if(strlen($fieldName) && strlen($fieldValue)){

	 	if($use_joomfish){
	 		$query = "SELECT DISTINCT v.id
	 			FROM #__custom_properties_fields AS f
				INNER JOIN #__custom_properties_values as v
	 			LEFT JOIN #__jf_content AS jfcf ON jfcf.reference_id = f.id
	 			LEFT JOIN #__languages as jflf ON jfcf.language_id = jflf.id
	 			LEFT JOIN #__jf_content AS jfcv ON jfcv.reference_id = v.id
	 			LEFT JOIN #__languages as jflv ON jfcv.language_id = jflv.id
	 			WHERE f.access <= '$aid'
	 			AND ( (jfcf.value = '$fieldName' AND jflf.code = '$language')
	 			OR f.label = '$fieldName'  )
	 			AND ( (jfcv.value = '$fieldValue' AND jflv.code = '$language')
	 			OR v.label = '$fieldValue' ) ";
	 	}
	 	else{
			$query = "SELECT v.id
				FROM #__custom_properties_fields AS f
				INNER JOIN #__custom_properties_values AS v
					ON (f.id = v.field_id )
					WHERE f.access <= '$aid'
					AND f.label = '$fieldName'
					AND v.label = '$fieldValue' ";
	 	}
		$database->setQuery("$query");

		if($result = $database->loadResult()){
			return $result;
		}
	}
	else {
		return false;
	}
}
