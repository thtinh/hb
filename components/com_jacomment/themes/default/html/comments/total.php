<?php
	defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="jac-total-comment">
	<h2 class="componentheading"><span id="jac-number-total-comment"><?php echo $this->totalAll; ?> <?php if($this->totalAll > 1){echo JText::_("Comments");}else{echo JText::_("Comment");}?></span>
		<?php if($isEnableRss){?>
			<a id="jac-rss" href="<?php echo $this->linkRss;?>">
				<?php
					$fileTemplate  =  JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS."com_jacomment".DS."themes".DS.$theme.DS."images".DS."rss.gif";
					$linkFile			 = "";
					if(file_exists($fileTemplate)){
						$linkFile =  'templates/'.$mainframe->getTemplate().'/html/com_jacomment/themes/'.$theme.'/images/rss.gif';	
					 }else{		 			 	
						if(file_exists('components/com_jacomment/themes/'.$theme.'/images/rss.gif')){		 			
							$linkFile =  'components/com_jacomment/themes/'.$theme.'/images/rss.gif';	
						}else{
							$linkFile =  'components/com_jacomment/themes/default/images/rss.gif';	
						}
					 }		
				 ?>						 		
				<img alt="<?php echo JText::_("Rss");?>" src="<?php echo $linkFile;?>"/>		
			</a>
		<?php }?>
	</h2>	
</div>