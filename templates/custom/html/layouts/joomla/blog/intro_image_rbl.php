<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$params = $displayData->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$images = json_decode($displayData->images);

?>
<?php if (!empty($images->image_intro)) : ?>
    <?php $imgfloat = empty($images->float_intro) ? $params->get('float_intro') : $images->float_intro; ?>
    <div class="item-image img-thumbnail post-image post-image-16-10 no-hover-zoom">
            <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                <a class="image-wrapper" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->id, $displayData->catid, $displayData->language)); ?>" title="<? $displayData->title; ?> ">
	                <?= JLayoutHelper::render('joomla.blog.post_image_intro', $displayData);?>
                </a>
            <?php else : ?>
                <div class="image-wrapper">
                    <img
                        <?php if ($images->image_intro_caption) : ?>
                            <?php echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_intro_caption, ENT_COMPAT, 'UTF-8') . '"'; ?>
                        <?php endif; ?>
                        src="<?php echo htmlspecialchars($images->image_intro, ENT_COMPAT, 'UTF-8'); ?>"
                        alt="<?php echo htmlspecialchars($images->image_intro_alt, ENT_COMPAT, 'UTF-8'); ?>"
                        itemprop="thumbnailUrl"/>
                </div>
            <?php endif; ?>
    </div>
<?php endif; ?>
