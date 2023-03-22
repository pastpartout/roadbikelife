<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.tooltip', '.hasTooltip', [
    'placement' => 'right',
]);

extract($displayData);

$item = $page['data'];

$display = $item->text;
$app = Factory::getApplication();

foreach ($pages["pages"] as $key => $pageItem) {
    if ($pageItem['data']->active === true) {
        $activePage = $key;
        break;
    };
}

switch ((string)$item->text) {

    // Check for "Prev" item
    case $item->text === Text::_('JPREV'):
        $icon = 'angle-up';
        $text = 'zu Seite'.$activePage-1;
        $aria = 'Zu Seite ' . $activePage-1 . ' von ' . count($pages['pages']);
        $class = 'prev';
        $disabled = ($activePage === 1);
        break;

    // Check for "Next" item
    case Text::_('JNEXT'):
        $icon = 'angle-down';
        $text = 'zu Seite'.$activePage+1;
        $aria = 'Zu Seite ' . $activePage+1 . ' von '. count($pages['pages']);
        $class = 'next';
        $disabled = ($activePage === count($pages['pages']));
        break;

}
$link = 'href="' . $item->link . '"';

?>
    <li class="page-item">
        <a aria-label="<?php echo $aria; ?>" <?php echo $link; ?> class="fade hasTooltip <?php if($disabled === false):?> page-nav-link page-nav-link-<?= $class; ?> text-white<?php else: ?>text-white-50<?php endif ?>" <?php if($disabled === false):?> title="<?= $aria ?>"<?php endif ?>>
            <i class="fal fa-2x fa-fw fa-<?php echo $icon; ?>"></i>
        </a>
    </li>

