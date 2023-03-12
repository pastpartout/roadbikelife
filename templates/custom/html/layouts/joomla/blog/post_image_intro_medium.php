<?php
defined('_JEXEC') or die;

JLoader::register('RoadbikelifeModelResizeimage', JPATH_BASE . '/components/com_roadbikelife/models/resizeimage.php');
$imageModel = new RoadbikelifeModelResizeimage();
$images = json_decode($displayData->images);

?>
<picture>
    <source
            media="
  (-webkit-min-device-pixel-ratio: 2)      and (max-width: 480px),
             (   min--moz-device-pixel-ratio: 2)      and (max-width: 480px),
             (     -o-min-device-pixel-ratio: 2/1)    and (max-width: 480px),
             (        min-device-pixel-ratio: 2)      and (max-width: 480px),
             (                min-resolution: 192dpi) and (max-width: 480px),
             (                min-resolution: 2dppx)  and (max-width: 480px)
" type="image/webp"
            srcset="<?= $imageModel->getResizedImagePath($images->image_intro, 800, null, 1) ?>">

    <source
            media="(max-width: 480px)" type="image/webp"
            srcset="<?= $imageModel->getResizedImagePath($images->image_intro, 480, null,1) ?>">
    <source  type="image/webp"
             srcset="<?= $imageModel->getResizedImagePath($images->image_intro, 600,null,1) ?>">
    <source
            media="
  (-webkit-min-device-pixel-ratio: 2)      and (max-width: 480px),
             (   min--moz-device-pixel-ratio: 2)      and (max-width: 480px),
             (     -o-min-device-pixel-ratio: 2/1)    and (max-width: 480px),
             (        min-device-pixel-ratio: 2)      and (max-width: 480px),
             (                min-resolution: 192dpi) and (max-width: 480px),
             (                min-resolution: 2dppx)  and (max-width: 480px)
"
            srcset="<?= $imageModel->getResizedImagePath($images->image_intro, 800, null, 0) ?>">

    <source
            media="(max-width: 480px)"
            srcset="<?= $imageModel->getResizedImagePath($images->image_intro, 500,null) ?>">
    <source
            srcset="<?= $imageModel->getResizedImagePath($images->image_intro, 600,null) ?>">
    <img loading="lazy"
            src="<?= $imageModel->getResizedImagePath($images->image_intro, 1000,null) ?>"
            alt="<?php echo htmlspecialchars($displayData->title, ENT_COMPAT, 'UTF-8'); ?>"
            />
</picture>
