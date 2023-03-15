<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\Registry\Registry;

JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');
$imageModel = new RoadbikelifeModelResizeimage();

$authorised = JFactory::getUser()->getAuthorisedViewLevels();
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$view = $app->input->getCmd('view', '');
extract($displayData);
$images = json_decode($nextItem->images);

?>
<?php if (!empty($displayData)) : ?>
    <div class="frontpage-grid-item-next">
        <div class="d-flex justify-content-center">
            <?php if (isset($nextItem)): ?>

                <a href="#item-<?= $nextItem->id ?>"
                   class="text-muted  text-decoration-none col-auto text-center d-flex border-right pr-3 mr-3">
                    <i class="fal fa-2x mr-3 fa-angle-double-down"></i>
                    <div class="py-1 font-weight-light">Nächster Beitrag</div>
                </a>
                <div class="col-title ps-3 d-flex align-items-center">
                    <div class="">
                        <div class="item-image post-image post-image-1-1 d-none">
                            <a class="image-wrapper" href="#item-<?= $nextItem->id ?>"
                               title="<?= $nextItem->title; ?> ">
                                <picture>
                                    <source type="image/webp"
                                            srcset="<?= $imageModel->getResizedImagePath($images->image_intro, 200, null, 1) ?> 400w">
                                    <source srcset=" <?= $imageModel->getResizedImagePath($images->image_intro, 200, null) ?> 400w">
                                    <img
                                            src="<?= $imageModel->getResizedImagePath($images->image_intro, 200, null) ?>"
                                            alt="<?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8'); ?>"
                                            class="<?= $item->css_class ?>"
                                    />
                                </picture>
                            </a>

                        </div>
                        <a href="#item-<?= $nextItem->id ?>" class="h4 mb-0 font-weight-light text-white">
                            <?= $nextItem->title ?>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                Weiter zur nächsten Seite
            <?php endif; ?>

        </div>
    </div>
<?php endif; ?>
