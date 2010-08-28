<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.6 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Customproperties Configuration Model
 *
  * @package Custom Properties
  * @version 1.90
  * @subpackage Component
 */
class CustompropertiesModelConfig extends JModel
{
  /**
   * Associative array of parameters
   *
   * @var array
   */
  var $parameters;
  /**
   * Id file writable
   *
   * @var boolean
   */
  var $_is_writable;
  /**
   * Complete path of config file
   *
   * @var string
   */
  var $_config_file;

  /**
   * Constructor that retrieves the ID from the request
   *
   * @access    public
   * @return    void
   */
  function __construct()
  {
    parent::__construct();
    $this->_init();
  }

  /**
  * Initializes model. Creates a stub for config file is missing.
  *
  * @returns void
  */
  function _init(){

    $cp_config_path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_customproperties'.DS;
    $cp_config_file = 'cp_config.php';
    $this->_config_file = $cp_config_path.$cp_config_file;

    // if config file does not exists, make sure dir is writable
    if(!file_exists($this->_config_file)){
      if(!is_writable($cp_config_path)){
        JError::raiseError( 500, JText::sprintf( 'WARNNOTWRITABLE',$cp_config_path ) );
      }
      else{
        touch($this->_config_file);
      }
    }

  }
  /**
   * Retrieves the parameters from config file
   * @return Array
   */
  function getParameters()
  {
      // Load the data
      if (empty( $this->parameters )) {
        if(file_exists($this->_config_file)){
          require_once($this->_config_file);
          if(empty($cp_config)){
            $cp_config = $this->_defaultPars();
          }
        }
        else{
          $cp_config = $this->_defaultPars();
        }
        $this->parameters = $cp_config;
      }
      return $this->parameters;
  }
  /**
   * Checks if config file is writable.
   * @return boolean  true if config file is writable, false otherwise
   */
  function isWritable(){
    global $mainframe;
    @chmod ($this->_config_file, 0766);
    if (!is_callable(array("JFile","write")) || ($mainframe->getCfg('ftp_enable') != 1)) {
      $permission = is_writable($this->_config_file);
      $this->_is_writable = $permission;
    }
    else{
      $this->_is_writable = false;
    }
    return $this->_is_writable;

  }
  /**
   * Sets default parameters if config file is missing
   *
   * @access private
   * @returns array default parameters
   */
  function _defaultPars(){
    $cp_config = array();
	$cp_config['pageclass_sfx']='';
	$cp_config['header']='';
	$cp_config['show_page_title']='1';
	$cp_config['script_position']='auto';
	$cp_config['use_joomfish']='1';
	$cp_config['search_unauthorized']='1';
	$cp_config['search_archived']='1';
	$cp_config['result_acl']='0';
	$cp_config['frontend_tagging']='0';
	$cp_config['use_itemid']='1';
	$cp_config['editing_level']='1';
	$cp_config['limit']='';
	$cp_config['search_sections']='';
	$cp_config['default_ordering']='newest';
	$cp_config['ordering_field']='0';
	$cp_config['show_content_element_label']='0';
	$cp_config['view']='0';
	$cp_config['allowed_tags']='<h3><h4><h5><a><p>  ';
	$cp_config['text_length']='200';
	$cp_config['search_header_tl']='1';
	$cp_config['search_header_tr']='2';
	$cp_config['search_header_bl']='3';
	$cp_config['search_header_br']='0';
	$cp_config['show_result_summary']='0';
	$cp_config['show_content_element']='1';
	$cp_config['show_section']='0';
	$cp_config['linked_result_summary']='1';
	$cp_config['show_tags']='1';
	$cp_config['linked_tags']='1';
	$cp_config['show_tag_name']='0';
	$cp_config['url_format']='0';
	$cp_config['image_thumbnail']='1';
	$cp_config['thumb_width']='100';
	$cp_config['thumb_height']='100';
	$cp_config['keep_aspect']='1';
	$cp_config['image_quality']='75';
	$cp_config['debug']='0';
    return $cp_config;
  }
  /**
  * Method to save configuration on file
  *
  * @returns true if save succedded , false otherwise
  */
  function saveConfig(){

    $txt = "<?php\n";
    foreach ($_POST as $k=>$v) {
      if (strpos( $k, 'cfg_' ) === 0) {

         if (!get_magic_quotes_gpc()) {
            $v = addslashes( $v );
         }

     $txt .= "\$cp_config['".substr( $k, 4 )."']='$v';\n";
      }
    }
    $txt .= "?>";

    if (false && is_callable(array("JFile","write"))) {
      $result = JFile::write( $this->_config_file );
      if($result === false) $this->setError("Can't write config file with JFile::write");
    }
    else {
      $result = false;
      if ($fp = fopen( $this->_config_file, "w")) {
        $result = fwrite($fp, $txt, strlen($txt));
        fclose ($fp);
        if($result === false) $this->setError("Can't write config file with fwrite");
      }
      else{
        $this->setError("Can't open config file in write mode");
      }
    }

    return $result;
  }
}
