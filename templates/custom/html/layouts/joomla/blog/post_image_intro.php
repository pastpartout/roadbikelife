<?php
defined('_JEXEC') or die;

require_once JPATH_BASE.'/components/com_roadbikelife/models/resizeimage.php';
$imageModel = new RoadbikelifeModelResizeimage();
$images = json_decode($displayData->images);
if(!isset($images) && isset($displayData->core_images)) {
    $images = json_decode($displayData->core_images);
}
?>
<picture >
    <source type="image/webp"
        srcset="
        <?= $imageModel->getResizedImagePath($images->image_intro,400,null,1)  ?> 400w,
        <?= $imageModel->getResizedImagePath($images->image_intro,500,null,1)  ?> 500w,
        <?= $imageModel->getResizedImagePath($images->image_intro,900,null,1)  ?> 900w,
        <?= $imageModel->getResizedImagePath($images->image_intro,1300,null,1)  ?> 1300w,
        <?= $imageModel->getResizedImagePath($images->image_intro,1500,null,1)  ?> 1500w,
        <?= $imageModel->getResizedImagePath($images->image_intro,2000,null,1)  ?> 2000w"
    >
    <source
        srcset="
        <?= $imageModel->getResizedImagePath($images->image_intro,400,null)  ?> 400w,
        <?= $imageModel->getResizedImagePath($images->image_intro,500,null)  ?> 500w,
        <?= $imageModel->getResizedImagePath($images->image_intro,900,null)  ?> 900w,
        <?= $imageModel->getResizedImagePath($images->image_intro,1300,null)  ?> 1300w,
        <?= $imageModel->getResizedImagePath($images->image_intro,1500,null)  ?> 1500w,
        <?= $imageModel->getResizedImagePath($images->image_intro,2000,null)  ?> 2000w"
    >
    <img
            src="<?= $imageModel->getResizedImagePath($images->image_intro,800,null)  ?>"
            alt="<?php echo htmlspecialchars($displayData->title, ENT_COMPAT, 'UTF-8'); ?>"
            class="<?= $displayData->css_class ?>"
    />
</picture>
