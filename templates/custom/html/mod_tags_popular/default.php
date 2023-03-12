<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<?php JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php'); ?>
<div class="tagspopular mb-3">
    <?php if (!count($list)) : ?>
        <div class="alert alert-no-items"><?php echo JText::_('MOD_TAGS_POPULAR_NO_ITEMS_FOUND'); ?></div>
    <?php else : ?>
        <ul class="tags inline">
            <?php foreach ($list as $item) : ?>
                <li class="" itemprop="keywords">
                    <a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($item->tag_id . ':' . $item->alias)); ?>"
                       class="tag tag-primarySpecial small">
                        <?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8'); ?>
                        <?php if ($display_count) : ?>
                                <span class="tag-count badge font-weight-bold text-white">
                                    <?php echo $item->count; ?>
                                </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
