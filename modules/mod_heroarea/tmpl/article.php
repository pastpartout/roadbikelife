<?php
/** @var object $images */
/** @var \Joomla\Component\Content\Administrator\Table\ArticleTable $article */

defined('JPATH_BASE') or die;
require_once JPATH_BASE . '/components/com_roadbikelife/models/resizeimage.php';

?>
<section class="hero-area-blog <?php if ($images->image_fulltext == ''): ?>default-image<?php endif ?>  <?php if ($hasSidebar): ?>hasSidebar<?php endif ?>">
    <a href="#article-text">
        <picture>

            <?php if ($images->image_fulltext != ''): ?>
                <?php if ($images->image_fulltext_alt != '{"imagefile":"","alt_text":""}' && isset($images->image_fulltext_alt)): ?>
                    <source media="(max-width: 767px)" type="image/webp" class="exclude-lazyload"
                            srcset="
                        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext_alt, 400, null, 1) ?> 400w,
                        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext_alt, 800, null, 1) ?> 800w,
                        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext_alt, 1300, null, 1) ?> 1300w"
                    >
                    <source media="(max-width: 767px)" class="exclude-lazyload"
                            srcset="
                        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext_alt, 400, null) ?> 400w,
                        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext_alt, 800, null) ?> 800w,
                        <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext_alt, 1300, null) ?> 1300w"
                    >
                <?php endif ?>
                <?php if ($images->image_fulltext != ''): ?>
                    <source type="image/webp" class="exclude-lazyload"
                            srcset="
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 400, null, 1) ?> 400w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 900, null, 1) ?> 900w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 1500, null, 1) ?> 1500w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 2000, null, 1) ?> 2000w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 2500, null, 1) ?> 2500w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 3000, null, 1) ?> 3000w"
                    >
                    <source class="exclude-lazyload"
                            srcset="
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 400, null) ?> 400w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 900, null) ?> 900w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 1500, null) ?> 1500w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 2000, null) ?> 2000w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 2500, null) ?> 2500w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 3000, null) ?> 3000w"
                    >
                    <img <?php if (isset($imagePosition)): ?>
                        style="object-position: center <?= $imagePosition ?>"<?php endif ?>
                            itemprop="image"
                            src="<?= RoadbikelifeModelResizeimage::resizeImage($images->image_fulltext, 2000, null) ?>"
                            alt="<?php echo htmlspecialchars($article->title, ENT_COMPAT, 'UTF-8'); ?>"
                            class="bg-image img-fluid exclude-lazyload">
                <?php endif ?>
            <?php else: ?>
                <source type="image/webp" class="exclude-lazyload"
                        srcset="
                    <? $defaultImgUrl = 'templates/custom/img/bg_hero_area.jpg' ?>
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 400, null, 1) ?> 400w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 900, null, 1) ?> 900w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 1500, null, 1) ?> 1500w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 2000, null, 1) ?> 2000w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 2500, null, 1) ?> 2500w"
                >
                <source class="exclude-lazyload"
                        srcset="
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 400, null, 0) ?> 400w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 900, null, 0) ?> 900w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 1500, null, 0) ?> 1500w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 2000, null, 0) ?> 2000w,
                    <?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 2500, null, 0) ?> 2500w"
                >
                <img class="bg-image img-fluid exclude-lazyload"
                     itemprop="image"
                     src="templates/custom/img/bg_hero_area.jpg"
                     alt="<?php echo htmlspecialchars($article->title, ENT_COMPAT, 'UTF-8'); ?>"
                    <?php if (isset($imagePosition)): ?>
                        style="object-position: center <?= $imagePosition ?>"
                    <?php endif ?>
                />
            <?php endif ?>
        </picture>
    </a>
    <?php if ($article->catid == 8): ?>
        <div class="post-img-overlay ">
<!--            --><?php //if (!$hasSidebar): ?><!--<div class="container">--><?php //endif ?>
            <div class="row w-100 align-items-end">
                <div class="col-md-6 col-lg-7">
                    <div class="page-header">
                        <?= JLayoutHelper::render('rbl.common.btn_like_n_comments', ['item' => $article]); ?>
                        <h1 class="text-shadow" itemprop="name">
                            <?php echo $article->title; ?>
                        </h1>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 h-100 align-items-end d-none d-lg-flex pb-lg-3">
                    <?php
                    jimport('joomla.application.module.helper');
                    $module = JModuleHelper::getModule('mod_article_fwgallery', 'Weitere Bilder Headbereich');
                    echo JModuleHelper::renderModule($module);
                    ?>
                </div>
            </div>
<!--            --><?php //if (!$hasSidebar): ?><!--</div>--><?php //endif ?>

        </div>
    <?php endif ?>
</section>
<div class="w-100 d-flex d-lg-none">
    <?php
    jimport('joomla.application.module.helper');
    $module = JModuleHelper::getModule('mod_article_fwgallery', 'Weitere Bilder Headbereich');
    echo JModuleHelper::renderModule($module);
    ?>
</div>
