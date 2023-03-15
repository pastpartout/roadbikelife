<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Registry\Registry;

$list = $displayData['list'];
$pages = $list['pages'];

$options = new Registry($displayData['options']);

$showLimitBox = $options->get('showLimitBox', false);
$showPagesLinks = $options->get('showPagesLinks', true);
$showLimitStart = $options->get('showLimitStart', true);

// Calculate to display range of pages
$currentPage = 1;
$range = 1;
$step = 5;

if (!empty($pages['pages'])) {
    foreach ($pages['pages'] as $k => $page) {
        if (!$page['active']) {
            $currentPage = $k;
        }
    }
}

if ($currentPage >= $step) {
    if ($currentPage % $step === 0) {
        $range = ceil($currentPage / $step) + 1;
    } else {
        $range = ceil($currentPage / $step);
    }
}
?>
<ul class="frontpage-grid-dots">
    <?php echo LayoutHelper::render('rbl.pagination.frontpage_link', ['page' => $pages['previous'], 'pages' => $pages]); ?>
    <?php foreach ($options->get('items') as $key => $item): ?>
        <li>
            <a href="#item-<?= $item->id ?>" class="dot <?php if ($key === 0): ?>active<?php endif ?>"
               id="dot-<?= $item->id ?>"></a>
        </li>
    <?php endforeach ?>
    <?php echo LayoutHelper::render('rbl.pagination.frontpage_link', ['page' => $pages['next'], 'pages' => $pages]); ?>
</ul>
