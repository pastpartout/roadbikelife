<?
/** @var boolean $isSmartphone */

/** @var object $displayData */

use Joomla\CMS\Factory;

$app = Factory::getApplication();
$isSmartphone = ($app->client->mobile === true);
extract($displayData);
$images = json_decode($item->images);
$params = $item->params;
$index === 0 ? $isFirst = true : $isFirst = false;

?>
<article
        data-href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>">
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
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 400, null, 1) ?> 400w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 500, null, 1) ?> 500w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 900, null, 1) ?> 900w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 1300, null, 1) ?> 1300w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 1500, null, 1) ?> 1500w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 2000, null, 1) ?> 2000w"
                        >
                        <source
                                srcset="
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 400, null) ?> 400w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 500, null) ?> 500w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 900, null) ?> 900w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 1300, null) ?> 1300w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 1500, null) ?> 1500w,
        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 2000, null) ?> 2000w"
                        >
                        <img
                                <?php if(!$isFirst):?>
                                    loading="lazy"
                                <?php else: ?>
                                    loading="eager"
                                <?php endif; ?>
                                src="<?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 800, null) ?>"
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
                            loading="lazy"
                            src="<?php echo htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'UTF-8'); ?>"
                            alt="<?php echo htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'UTF-8'); ?>"
                            itemprop="thumbnailUrl"/>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <img
            src="<?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 300, null) ?>"
            alt="<?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8'); ?>"
            class="post-img-overlaybg"
    />
    <div class="post-img-overlay">
        <div>
            <?= JLayoutHelper::render('rbl.common.btn_like_n_comments', $displayData); ?>
            <div class="article-date text-white-50 d-none d-md-inline-block">
                <small>
                    <i class="fal fa-calendar icon-margin-right"></i>
                    <time datetime="<?php echo JHtml::_('date', $item->publish_up, 'c'); ?>"
                          itemprop="datePublished"><?php echo JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC4')); ?></time>
                </small>
            </div>
            <div class="headline">
                <h2 class="h3">
                    <a
                            href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"
                            class="text-decoration-none text-white">
                        <?php echo $item->title; ?>
                    </a>
                </h2>
            </div>
            <div class="introtext d-none d-lg-flex ">
                <p>
                    <?= $item->introtext; ?>
                </p>
            </div>
            <a class="btn btn-primary w-100"
               href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>">
                <i lass="fa fa-angle-right icon-margin-right"></i><?php echo JText::_('READ_MORE'); ?>
            </a>
        </div>
    </div>
</article>