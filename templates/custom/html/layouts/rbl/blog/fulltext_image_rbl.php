<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
extract($displayData);
$params = $item->params;
$imageModel = new RoadbikelifeModelResizeimage();
$images = json_decode($item->images);
if (!isset($aspectRatio)) $aspectRatio = ''
?>
<?php if (!empty($images->image_fulltext)) : ?>
    <?php $imgfloat = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
    <div class="item-image post-image post-image-<?= $aspectRatio ?> no-hover-zoom">
        <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
            <a class="image-wrapper"
               href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $displayData->catid, $item->language)); ?>"
               title="<?= $item->title; ?> ">
                <picture>
                    <source type="image/webp"
                            srcset="
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 400, null, 1) ?> 400w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 500, null, 1) ?> 500w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 900, null, 1) ?> 900w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 1300, null, 1) ?> 1300w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 1500, null, 1) ?> 1500w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 2000, null, 1) ?> 2000w"
                    >
                    <source
                            srcset="
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 400, null) ?> 400w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 500, null) ?> 500w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 900, null) ?> 900w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 1300, null) ?> 1300w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 1500, null) ?> 1500w,
        <?= $imageModel->getResizedImagePath($images->image_fulltext, 2000, null) ?> 2000w"
                    >
                    <img
                            src="<?= $imageModel->getResizedImagePath($images->image_fulltext, 800, null) ?>"
                            alt="<?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8'); ?>"
                            class="<?= $item->css_class ?>"
                    />
                </picture>
            </a>
        <?php else : ?>
            <div class="image-wrapper">
                <img
                    <?php if ($images->image_fulltext_caption) : ?>
                        <?php echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'UTF-8') . '"'; ?>
                    <?php endif; ?>
                        src="<?php echo htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'UTF-8'); ?>"
                        alt="<?php echo htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'UTF-8'); ?>"
                        itemprop="thumbnailUrl"/>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
