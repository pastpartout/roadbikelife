<?
/** @var boolean $isSmartphone */
/** @var object $displayData */

use Joomla\CMS\Factory;

$app          = Factory::getApplication();
$isSmartphone = ($app->client->mobile === true);

?>
<article
        data-href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->id, $displayData->catid, $displayData->language)); ?>">
	<?php echo JLayoutHelper::render('joomla.blog.intro_image_rbl', $displayData); ?>
    <div class="postImageOverlayContent">
        <div>
            <div class="article-date">
                <small>
                    <i class="fal fa-calendar icon-margin-right"></i>
                    <time datetime="<?php echo JHtml::_('date', $displayData->publish_up, 'c'); ?>"
                          itemprop="datePublished"><?php echo JHtml::_('date', $displayData->publish_up, JText::_('DATE_FORMAT_LC4')); ?></time>
                </small>
            </div>
            <div class="headline">
                <h2 class="h3">
					<?php echo $displayData->title; ?>
                </h2>
            </div>
        </div>

    </div>
	<?php if (!$isSmartphone === true): ?>
        <div class="introtext">
            <div class="h4 mb-3">
				<?php echo $displayData->title; ?>
            </div>
            <div class="text">
                <p>
					<?= $displayData->introtext; ?>
                </p>
            </div>
			<?php echo JLayoutHelper::render('joomla.blog.tags_rbl_fp', $displayData->tags->itemTags); ?>
            <div class="pt-3 d-flex align-items-center">
                <span class="btn btn-outline-light"><i
                            class="fa fa-angle-right icon-margin-right"></i><?php echo JText::_('READ_MORE'); ?></span>
            </div>
        </div>
	<?php endif ?>
</article>
