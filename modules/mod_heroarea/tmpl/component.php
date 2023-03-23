<?php
defined('JPATH_BASE') or die;
require_once JPATH_BASE.'/components/com_roadbikelife/models/resizeimage.php';

//$active = JFactory::getApplication()->getMenu()->getActive();
//$title = $active->title;
$defaultImgUrl = 'templates/custom/img/bg_hero_area.jpg';
?>
<section class="hero-area-blog default-image">
    <picture>
        <source type="image/webp" class="exclude-lazyload"
                srcset="
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
             src="<?= RoadbikelifeModelResizeimage::resizeImage($defaultImgUrl, 1500, null, 0) ?>"
             alt="<?php echo htmlspecialchars('roadbikelife Rennrad Blog', ENT_COMPAT, 'UTF-8'); ?>"
            <?php if (isset($imagePosition)): ?>
                style="object-position: center <?= $imagePosition ?>"
            <?php endif ?>
        />
    </picture>
<!--    <div class="post-img-overlay">-->
<!--        <div class="row w-100 align-items-center">-->
<!--            <div class="col-md-6 col-lg-7">-->
<!--                <div class="page-header">-->
<!--                    <div class="h1 text-shadow" itemprop="name">-->
<!--                        --><?php //echo $title; ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </div>-->
</section>
