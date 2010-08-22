<?php
// Try extending time, as unziping/ftping took already quite some... :
@set_time_limit( 240 );
defined ( '_JEXEC' ) or die ( 'Restricted access' );

require_once( JPATH_SITE .DS. 'components'.DS.'com_jacomment'.DS.'helpers'.DS.'jahelper.php');

function com_install() {  
  JACommentHelpers::Install_Db();
  	
  # Show installation result to user
  ?>
 <div style="text-align:left;">
  	<table width="100%" border="0" style="line-height:200%; font-weight:bold;">	  
	    <tr>
	      <td align="center">
	      		<img src="components/com_jacomment/asset/images/jacoment.png" />
	      		JA Comment is installed successfully! Thank's <a href=http://joomfans.com>Joomfans.com</a> team...<br/>
				<a href="http://wiki.joomlart.com/wiki/JACOMMENT/Installation_Guides" title="Read more">Read more</a>
	      </td>
	    </tr>
    </table>
 </div>
<?php }?>