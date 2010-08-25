<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
    <table class="adminform">
        <tr>
            <td width="55%" valign="top">
                <div id="cpanel">
                    <?php
                    $link = 'index.php?option=com_phocagallery&view=phocagallerys';
                    echo PhocaGalleryRenderAdmin::quickIconButton($link, 'icon-48-pg-gal.png', JText::_('Images'));

                    $link = 'index.php?option=com_phocagallery&view=phocagallerycs';
                    echo PhocaGalleryRenderAdmin::quickIconButton($link, 'icon-48-pg-cat.png', JText::_('Categories'));

                    $link = 'index.php?option=com_phocagallery&view=phocagallerycos';
                    echo PhocaGalleryRenderAdmin::quickIconButton($link, 'icon-48-pg-comment.png', JText::_('PHOCAGALLERY_CATEGORY_COMMENTS'));
                    $link = 'index.php?option=com_phocagallery&view=phocagallerycoimgs';
                    echo PhocaGalleryRenderAdmin::quickIconButton($link, 'icon-48-pg-comment-img.png', JText::_('PHOCAGALLERY_IMAGE_COMMENTS'));

         
                    ?>

                    <div style="clear:both">&nbsp;</div>
                    <p>&nbsp;</p>



                </div>
            </td>

            <td width="45%" valign="top">
                
            </td>
        </tr>
    </table>

    <input type="hidden" name="option" value="com_phocagallery" />
    <input type="hidden" name="view" value="phocagallerycp" />
    <input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
</form>