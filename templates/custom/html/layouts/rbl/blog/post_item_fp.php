<?
/** @var boolean $isSmartphone */

/** @var object $displayData */

use Joomla\CMS\Factory;

$app = Factory::getApplication();
$isSmartphone = ($app->client->mobile === true);
extract($displayData);
$images = json_decode($item->images);
$imageModel = new RoadbikelifeModelResizeimage();

?>
<article
        data-href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>">
    <?php echo JLayoutHelper::render('rbl.blog.fulltext_image_rbl', ['item' => $item]); ?>
    <img
            src="<?= $imageModel->getResizedImagePath($images->image_fulltext, 300, null) ?>"
            alt="<?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8'); ?>"
            class="post-img-overlaybg"
    />
    <div class="post-img-overlay px-lg-5 py-lg-5">
        <div>
            <div class="article-date">
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

