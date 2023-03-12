<?php
defined('JPATH_BASE') or die;
require_once JPATH_BASE.'/components/com_roadbikelife/models/resizeimage.php';
$imageModel = new RoadbikelifeModelResizeimage();
//$active = JFactory::getApplication()->getMenu()->getActive();
//$title = $active->title;
$defaultImgUrl = 'templates/custom/img/bg_hero_area.jpg';
?>
<section class="hero-area-blog default-image">
    <picture>
        <source type="image/webp" class="exclude-lazyload"
                srcset="
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 400, null, 1) ?> 400w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 900, null, 1) ?> 900w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 1500, null, 1) ?> 1500w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 2000, null, 1) ?> 2000w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 2500, null, 1) ?> 2500w"
        >
        <source class="exclude-lazyload"
                srcset="
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 400, null, 0) ?> 400w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 900, null, 0) ?> 900w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 1500, null, 0) ?> 1500w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 2000, null, 0) ?> 2000w,
                    <?= $imageModel->getResizedImagePath($defaultImgUrl, 2500, null, 0) ?> 2500w"
        >
        <img class="bg-image img-fluid exclude-lazyload"
             src="<?= $imageModel->getResizedImagePath($defaultImgUrl, 1500, null, 0) ?>"
             alt="<?php echo htmlspecialchars('roadbikelife Rennrad Blog', ENT_COMPAT, 'UTF-8'); ?>"
            <?php if (isset($imagePosition)): ?>
                style="object-position: center <?= $imagePosition ?>"
            <?php endif ?>
        />
    </picture>
<!--    <div class="postImageOverlayContent">-->
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
