<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die;
;
$imagesCount = count($images);
$imagesLimit = 4;
$moreImagesCount = $imagesCount - $imagesLimit;
$doc->addScript('templates/custom/js/jquery.fancybox.min.js');
?>
<?php if (count($images) > 0) : ?>
    <div class="content-images">
        <a class="anchor" id="content-images"></a>
        <ul class="galleryImages row no-gutters mb-3 list-unstyled">
            <?php foreach ($images as $key => $image): ?>
                <li class="col-3 <?php if ($key >= $imagesLimit): ?>d-none<?php endif ?>">
                    <div class="item-image img-thumbnail post-image post-image-1-1 shadow-lg  ">
                        <div class="image-wrapper">
                            <a
                                    href="<?php echo JURI::base() . "index.php?option=com_fwgallery&view=item&layout=img&format=raw&id=$image->id"; ?>"
                                    data-fancybox="post-gallery"
                                <?php if (isset($image->has_caption)): ?>
                                    data-caption="<?php echo htmlspecialchars($image->name, ENT_COMPAT, 'UTF-8'); ?>"
                                <?php endif ?>
                            >
                                <img
                                        loading="lazy"
                                        class="lazyLoader"
                                        src="<?php echo JURI::base() . "index.php?option=com_fwgallery&view=item&layout=img&format=raw&w=150&h=150&id=" . urlencode($image->id); ?>"
                                        alt="<?php echo htmlspecialchars($image->name, ENT_COMPAT, 'UTF-8'); ?>"
                                        itemprop="thumbnailUrl"/>
                            </a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if ($key >= $imagesLimit): ?>
            <div class="moreImagesIconWrapper">
                <p class="small text-white-50 pt-2 mb-0 ">
                    <?php if (isset($moreImagesCount)  && $moreImagesCount > 1): ?>
                        <?= $moreImagesCount ?> weitere Bilder verfügbar
                    <?php else: ?>
                        <?= $moreImagesCount ?> weiteres Bilder verfügbar
                    <?php endif ?>
                </p>
            </div>
        <?php endif ?>
    </div>
<?php endif; ?>
