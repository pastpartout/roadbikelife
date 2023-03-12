<?php
defined('_JEXEC') or die();
require_once JPATH_BASE.'/components/com_roadbikelife/models/resizeimage.php';
$knm_img_model = new RoadbikelifeModelResizeimage();
?>



<div class="item-image img-thumbnail item-image-content">
    <a href="<?=  $knm_img_model->getResizedImagePath($displayData->src, 1200, null, null) ?>"
       data-fancybox="post-article">
        <picture class="img-wrapper">
            <source type="image/webp"
                    srcset="
        <?= $knm_img_model->getResizedImagePath($displayData->src, 400, null, 1) ?> 400w,
        <?= $knm_img_model->getResizedImagePath($displayData->src, 600, null, 1) ?> 600w,
        <?= $knm_img_model->getResizedImagePath($displayData->src, 900, null, 1) ?> 900w,
        <?= $knm_img_model->getResizedImagePath($displayData->src, 600, null, 1) ?> 600w,
        <?= $knm_img_model->getResizedImagePath($displayData->src, 1200, null, 1) ?> 1200w">
            <source
                    srcset="
        <?= $knm_img_model->getResizedImagePath($displayData->src, 400, null, null) ?> 400w,
        <?= $knm_img_model->getResizedImagePath($displayData->src, 600, null, null) ?> 600w,
        <?= $knm_img_model->getResizedImagePath($displayData->src, 900, null, null) ?> 900w,
        <?= $knm_img_model->getResizedImagePath($displayData->src, 1200, null, null) ?> 1200w">
            <img src="<?= $knm_img_model->getResizedImagePath($displayData->src, 2200) ?>"
                 alt="<?= $displayData->alt ?>" class="lazyLoader img-fluid" loading="lazy">
        </picture>
		<?php if (isset($displayData->alt)):?>
            <span class="subtitle">
            <?= $displayData->alt ?>
        </span>
		<?php endif ?>
    </a>
</div>