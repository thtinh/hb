<?php 
defined( '_JEXEC' ) or die( 'Restricted access' ); 
if(!isset($theme)) $theme ="default";
$session = &JFactory::getSession();
if(JRequest::getVar("jacomment_theme", '')){
	jimport( 'joomla.filesystem.folder' );
	$themeURL = JRequest::getVar("jacomment_theme");
	if(JFolder::exists('components/com_jacomment/themes/'.$themeURL)){
		$theme =  $themeURL;						
	}
	$session->set('jacomment_theme', $theme);			
}else{
	if($session->get('jacomment_theme', null)){
		$theme = $session->get('jacomment_theme', $theme);
	}
}
if($enableSmileys && !defined("JACOMMENT_GLOBAL_CSS_SMILEY")){
	$style = '
	       #jac-wrapper .plugin_embed .smileys{
	            top: 17px;
	        	background:#ffea00;
	            clear:both;
	            height:84px;
	            width:105px;	            
	            padding:2px 1px 1px 2px !important;
	            position:absolute;
	            z-index:51;
	            -webkit-box-shadow:0 1px 3px #999;box-shadow:1px 2px 3px #666;-moz-border-radius:2px;-khtml-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;
	        }        
	        #jac-wrapper .plugin_embed .smileys li{
	            display: inline;
	            float: left;
	            height:20px;
	            width:20px;
	            margin:0 1px 1px 0 !important;
	            border:none;
	            padding:0
	        }
	        #jac-wrapper .plugin_embed .smileys .smiley{
	            background: url('.JURI::base().'components/com_jacomment/asset/images/smileys/'.$smiley.'/smileys_bg.png) no-repeat;
	            display:block;
	            height:20px;
	            width:20px;
	        }
	        #jac-wrapper .plugin_embed .smileys .smiley:hover{
	            background:#fff;
	        }
	        #jac-wrapper .plugin_embed .smileys .smiley span{
	            background: url('.JURI::base().'components/com_jacomment/asset/images/smileys/'.$smiley.'/smileys.png) no-repeat;
	            display: inline;
	            float: left;
	            height:12px;
	            width:12px;
	            margin:4px !important;
	        }
	        #jac-wrapper .plugin_embed .smileys .smiley span span{
	            display: none;
	        } 
	        #jac-wrapper .comment-text .smiley {
	            font-family:inherit;
				font-size:100%;
				font-style:inherit;
				font-weight:inherit;
				text-align:justify;
	        }
	        #jac-wrapper .comment-text .smiley span{
	            background: url('.JURI::base().'components/com_jacomment/asset/images/smileys/'.$smiley.'/smileys.png) no-repeat scroll 0 0 transparent;
				display:inline;
				float:left;
				height:12px;
				margin:4px !important;
				width:12px;
	        }
	        .comment-text .smiley span span{
	            display:none;
	        }
	';
	$doc = & JFactory::getDocument();
	$doc->addStyleDeclaration($style);
}
?>
<?php	 
	if(!defined('JACOMMENT_PLUGIN_ATD')){JHTML::stylesheet('atd.css', 'components/com_jacomment/asset/css/');JHTML::script('jquery.atd.js', 'components/com_jacomment/libs/js/atd/');JHTML::script('csshttprequest.js', 'components/com_jacomment/libs/js/atd/');JHTML::script('atd.js', 'components/com_jacomment/libs/js/atd/');define('JACOMMENT_PLUGIN_ATD', true);}
?>
<script type="text/javascript">
//<![CDATA[	
	jQuery(document).ready(function($){	
		jac_init();		
	});
	
	var JACommentConfig = {
		jac_base_url 			: '<?php echo JURI::base();?>',
		siteurl 				: '<?php echo JURI::base()."index.php?tmpl=component&amp;option=com_jacomment&amp;view=comments";?>',
		minLengthComment 		: '<?php echo $minLength;?>',		
		errorMinLength 			: '<?php echo JText::_("Your comment is too short.");?>',
		maxLengthComment 		: '<?php echo $maxLength;?>',
		errorMaxLength 			: '<?php echo JText::_("Your comment is too long.");?>',			
		isEnableAutoexpanding  : '<?php echo $isEnableAutoexpanding;?>',
		dateASC					: '<?php echo JText::_("Date Ascending, latest comment on top");?>',
		dateDESC				: '<?php echo JText::_("Date  descending, latest comment in bottom ");?>',
		votedASC				: '<?php echo JText::_("Most rated on top");?>',
		strLogin				: '<?php echo JText::_("Login now");?>',
		isEnableBBCode			: '<?php echo $enableBbcode;?>',
		hdCurrentComment		: 0,
<?php if( isset($lists['contentoption'])){?>		
		contentoption			: '<?php echo $lists['contentoption'];?>',
		contentid				: '<?php echo $lists['contentid'];?>',
		commenttype				: '<?php echo $lists['commenttype'];?>',
		jacomentUrl				: '<?php echo $lists['jacomentUrl'];?>',
		contenttitle			: '<?php echo addslashes($lists['contenttitle']);?>',
<?php }?>				
		hidInputComment			: '<?php echo JText::_("You must input comment.");?>',
		hidInputWordInComment	: '<?php echo JText::_("The words are too long. You should add more spaces between them.");?>',
		hidEndEditText			: '<?php echo JText::_("Please exit Spell Check before submitting comment."); ?>',
		hidInputName			: '<?php echo JText::_("You must input Name.");?>',
		hidInputEmail			: '<?php echo JText::_("You must input Email.");?>',		
		hidAgreeToAbide			: '<?php echo JText::_("You must agree to abide by the Website rules.");?>',
		hidInputCaptcha			: '<?php echo JText::_("You must input text of captcha.");?>',
		textQuoting			    : '<?php echo JText::_("Quoting");?>',
		textQuote			    : '<?php echo JText::_("Quote");?>',
		textPosting			    : '<?php echo JText::_("Posting");?>',
		textReply			    : '<?php echo JText::_("Reply");?>',
		textCheckSpelling		: '<?php echo JText::_("No writing errors were found.");?>',
		mesExpandForm			: '<?php echo JText::_("(+) click to expand");?>',
		mesCollapseForm			: '<?php echo JText::_("(-) click to collapse");?>',
		theme					: '<?php echo $theme;?>'				
	};																	
//]]>
</script>
<?php if($isAttachImage){	
	$strTypeFile = JText::_("Support file type: ").$attachFileType." ".JText::_("only");		
	$arrTypeFile = explode(",", $attachFileType);			
	$strListFile = "";
	if ($arrTypeFile) {
		foreach ($arrTypeFile as $type){
			$strListFile .= "'$type',";
		}
		$strListFile .= '0000000';
	}	
	?>
	<script type="text/javascript">	
		JACommentConfig.v_array_type 	  = [ <?php echo $strListFile;?> ];	
		JACommentConfig.error_type_file   = "<?php echo $strTypeFile;?>";
		JACommentConfig.total_attach_file =	"<?php echo $totalAttachFile;?>";
		JACommentConfig.error_name_file   = "<?php echo JText::_("File name is too long.");?>";  
	</script>
	<script type="text/javascript" src="components/com_jacomment/asset/js/ja.upload.js"></script>
	<iframe id="upload_target" name="upload_target" src="#" style="width:0; height:0; border:0px solid #fff;"></iframe>
<?php }?>
<?php if($isEnableAutoexpanding){?><script type="text/javascript" src="components/com_jacomment/libs/js/jquery/jquery.autoresize.js"></script><?php }?>
<?php if($enableBbcode){?>
	<script type="text/javascript" src="components/com_jacomment/libs/js/dcode/dcodr.js"></script>
	<script type="text/javascript" src="components/com_jacomment/libs/js/dcode/dcode.js"></script>
<?php }?>	  
<?php if($enableYoutube){?>
<script language="javascript" type="text/javascript">
	function open_youtube(id){jacCreatForm('open_youtube',id,400,200,0,0,'<?php echo JText::_("Embed a YouTube Video");?>',0,'<?php echo JText::_("Embed Video");?>');}
</script>
<?php }?>	               