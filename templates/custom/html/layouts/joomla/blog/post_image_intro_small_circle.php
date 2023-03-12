<?php
defined('_JEXEC') or die;

require_once JPATH_BASE.'/components/com_roadbikelife/models/resizeimage.php';
$imageModel = new RoadbikelifeModelResizeimage();

$test = $imageModel->getResizedImagePath($displayData->image_intro, 200, null, 1);

?>

<picture>
    <source type="image/webp"
            srcset="<?= $imageModel->getResizedImagePath($displayData->image_intro, 300, null, 1) ?>">
    <img
            src="<?= $imageModel->getResizedImagePath($displayData->image_intro, 300, null) ?>"
            alt="<?php echo htmlspecialchars($displayData->title, ENT_COMPAT, 'UTF-8'); ?>"
            itemprop="image"/>
</picture>
