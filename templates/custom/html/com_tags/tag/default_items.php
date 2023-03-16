<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.core');

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');

JFactory::getDocument()->addScriptDeclaration("
		var resetFilter = function() {
		document.getElementById('filter-search').value = '';
	}
");

?>
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm"
      id="adminForm" class="form-inline">
    <?php if ($this->params->get('show_headings') || $this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
        <fieldset class="filters btn-toolbar">
            <?php if ($this->params->get('filter_field')) : ?>
                <div class="btn-group">
                    <label class="filter-search-lbl element-invisible" for="filter-search">
                        <?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?>
                    </label>
                    <input type="text" name="filter-search" id="filter-search"
                           value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox"
                           onchange="document.adminForm.submit();"
                           title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>"
                           placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>"/>
                    <button type="button" name="filter-search-button"
                            title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"
                            onclick="document.adminForm.submit();" class="btn">
                        <span class="icon-search"></span>
                    </button>
                    <button type="reset" name="filter-clear-button"
                            title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" class="btn"
                            onclick="resetFilter(); document.adminForm.submit();">
                        <span class="icon-remove"></span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->params->get('show_pagination_limit')) : ?>
                <div class="btn-group pull-right">
                    <label for="limit" class="element-invisible">
                        <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
                    </label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            <?php endif; ?>
            <input type="hidden" name="filter_order" value=""/>
            <input type="hidden" name="filter_order_Dir" value=""/>
            <input type="hidden" name="limitstart" value=""/>
            <input type="hidden" name="task" value=""/>
            <div class="clearfix"></div>
        </fieldset>
    <?php endif; ?>
    <?php if (empty($this->items)) : ?>
        <p><?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>
    <?php else : ?>
        <div class="category postColListBordered  list-unstyled  row">
            <?php foreach ($this->items as $i => $item) : ?>
                <?php $images = json_decode($item->core_images); ?>

                <div class="col-md-6 post postLink p-3 mb-4" href="<?php echo JRoute::_($item->link); ?>">
                    <a class="postLinkItem boxed boxed-dark" href="<?php echo JRoute::_($item->link); ?>">
                        <div class="item-image mb-3 post-image post-image-16-9  ">
                            <div class="image-wrapper">
                                <?= JLayoutHelper::render('joomla.blog.post_image_intro', $item);?>
                            </div>
                        </div>
                        <div class="inner text-body">
                            <div class="article-date">
                                <small>
                                    <i class="fal fa-calendar icon-margin-right"></i><time datetime="<?php echo JHtml::_('date', $item->core_publish_up, 'c'); ?>" itemprop="datePublished"><?php echo JHtml::_('date', $item->core_publish_up, JText::_('DATE_FORMAT_LC4')); ?></time>
                                </small>
                            </div>
                            <h3 itemprop="name">
                                <?php echo $item->core_title; ?>
                            </h3>
                            <div class="tag-body postLinkText">
                                <?php echo JHtml::_('string.truncate', strip_tags($item->core_body), $this->params->get('tag_list_item_maximum_characters')); ?>
                            </div>
                            <div class="mt-3 btn btn-layout-dark"
                                 href="<?php echo JRoute::_($item->link) ?>">
                                <i class="fa fa-angle-right icon-margin-right"></i><?php echo JText::_('READ_MORE'); ?>
                            </div>
                        </div>
                    </a>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</form>
