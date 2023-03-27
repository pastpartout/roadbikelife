<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/** @var plgcontentContentMedia $googleMapHtml */

defined('_JEXEC') or die;
require_once JPATH_BASE.'/components/com_roadbikelife/models/resizeimage.php';
extract($displayData);


if(strpos($size,'-') > 0) {
    $imageWidth =  400;
} else {
    $imageWidth =  1000;

}

?>
<div class="item-image img-thumbnail item-image-content <?= $size ?>">
    <a href="<?= $url ?>" data-fancybox="post-article">
        <picture>
            <source
                    media="(max-width: 480px)" type="image/webp"
                    srcset="<?= RoadbikelifeModelResizeimage::resizeImage($url, 600, null,1) ?>">
            <source
                    media="(max-width: 480px)"
                    srcset="<?= RoadbikelifeModelResizeimage::resizeImage($url, 600,null) ?>">
            <source type="image/webp"
                    srcset="<?= RoadbikelifeModelResizeimage::resizeImage($url, $imageWidth,null,1) ?>">
            <img  class="img-fluid"
                  loading="lazy"
                  src="<?= RoadbikelifeModelResizeimage::resizeImage($url, $imageWidth,null,0) ?>" alt="<?= $subTitle ?>" />
        </picture>
        <?php if (isset($subTitle)):?>
        <span class="subtitle">
            <?= $subTitle ?>
        </span>
        <?php endif ?>
    </a>
</div>
