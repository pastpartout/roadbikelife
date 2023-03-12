<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$lang = JFactory::getLanguage();

?>
<ul class="pager pagenav">
    <?php if ($row->prev) :
        $direction = $lang->isRtl() ? 'right' : 'left'; ?>
        <li class="previous">
            <a title="<?php echo htmlspecialchars($rows[$location - 1]->title); ?>"
               href="<?php echo $row->prev; ?>" rel="prev">
                <i class="fal fa-2x text-gradient mr-3 fa-angle-left"></i>
                <span>
                      <span class="font-weight-normal">Vorheriger Beitrag</span><br>
                <?php echo '<span class="font-weight-bold" aria-hidden="true">' . $row->prev_label . '</span>'; ?>
                </span>

            </a>
        </li>
    <?php endif; ?>
    <?php if ($row->next) :
        $direction = $lang->isRtl() ? 'left' : 'right'; ?>
        <li class="next text-right">
            <a title="<?php echo htmlspecialchars($rows[$location + 1]->title); ?>"
               href="<?php echo $row->next; ?>" rel="next">
                <span>
                <span class="font-weight-normal">Nächster Beitrag</span><br>
                <?php echo '<span class="font-weight-bold" aria-hidden="true">' . $row->next_label . '</span>'; ?>
                </span>
                <i class="fal fa-2x text-gradient ml-3 fa-angle-right"></i>

            </a>
        </li>
    <?php endif; ?>
</ul>
