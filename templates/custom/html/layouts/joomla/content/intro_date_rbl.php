<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
$params = $displayData->params;

JLoader::register('RoadbikelifeModelLike', 'components/com_roadbikelife/models/like.php');


?>
<div class="article-date mb-4 d-flex align-items-center justify-content-between">
    <div class="small">
        <?php if (!isset($params) || $params->get('show_publish_date')) : ?>
            <i class="fal fa-calendar text-black-50 icon-margin-right"></i><time datetime="
            <?php echo JHtml::_('date', $displayData->publish_up, 'c'); ?>" itemprop="datePublished">
                <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $displayData->publish_up, JText::_('DATE_FORMAT_LC4'))); ?>
            </time>
        <?php endif; ?>
    </div>
    <?= JLayoutHelper::render('joomla.blog.btn_like',$displayData); ?>
</div>
