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


$authorised = JFactory::getUser()->getAuthorisedViewLevels();
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$view = $app->input->getCmd('view', '');
extract($displayData);
$images = json_decode($nextItem->images);

?>
<?php if (!empty($displayData)) : ?>
    <div class="frontpage-item-next d-none d-lg-block">
        <div class="d-flex justify-content-center">
            <?php if (isset($nextItem)): ?>

                <a href="#item-<?= $nextItem->id ?>"
                   class="text-muted  text-decoration-none col-auto text-center d-flex border-right pr-3 mr-3">
                    <i class="fal fa-2x mr-3 fa-angle-double-down"></i>
                    <div class="py-1 font-weight-light">Nächster Beitrag</div>
                </a>
                <div class="col-title ps-3 d-flex align-items-center">
                    <div class="">
                        <a href="#item-<?= $nextItem->id ?>" class="h4 mb-0 font-weight-light text-white">
                            <?= $nextItem->title ?>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a class="d-flex align-items-center text-decoration-none" href="javascript:;" onclick="document.querySelector('.frontpage-dots .page-nav-link-next').click()">
                    <i class="fal fa-2x fa-angle-right mr-3"></i><span class="h4 font-weight-light mb-0">Weiter zur nächsten Seite</span>
                </a>
            <?php endif; ?>

        </div>
    </div>
<?php endif; ?>
