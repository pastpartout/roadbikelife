<?
/** @var boolean $isSmartphone */

/** @var object $displayData */

use Joomla\CMS\Factory;

$app = Factory::getApplication();
$isSmartphone = ($app->client->mobile === true);
extract($displayData);
?>
<article
        data-href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>">
    <?php echo JLayoutHelper::render('joomla.blog.intro_image_rbl', ['item' => $item]); ?>
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
                <h2 class="h2">
                    <?php echo $item->title; ?>
                </h2>
            </div>
            <div class="introtext d-none d-lg-flex ">
                <p>
                    <?= $item->introtext; ?>
                </p>
                <div class="btn btn-primary w-100">
                    <i lass="fa fa-angle-right icon-margin-right"></i><?php echo JText::_('READ_MORE'); ?>
                </div>
            </div>
        </div>
    </div>

</article>

