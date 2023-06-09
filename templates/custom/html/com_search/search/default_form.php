<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

?>
<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search'); ?>" method="post">
    <div class="input-group input-group-lg mb-3">
        <input type="text" name="searchword" title="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>"
               placeholder="<?php echo JText::_('SEARCH_KEYWORD_PLACEHOLDER'); ?>" id="search-searchword" size="30"
               maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>"
               class="form-control"/>
        <div class="input-group-append">
            <button name="Search" onclick="this.form.submit()" class="btn btn-primary rounded-right"
                    title="<?php echo JHtml::_('tooltipText', 'COM_SEARCH_SEARCH'); ?>">
                <span class="icon-search"></span>
                <i class="fa fa-search"></i>
            </button>
        </div>
        <input type="hidden" name="task" value="search"/>
    </div>
    <div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?> text-md-center ">
        <?php if (!empty($this->searchword)) : ?>
            <p>
                <?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', '<span class="badge badge-primary">' . $this->total . '</span>'); ?>
            </p>
        <?php endif; ?>
    </div>
    <?php if ($this->params->get('search_phrases', 1)) : ?>
        <fieldset class="phrases">
            <legend>
                <?php echo JText::_('COM_SEARCH_FOR'); ?>
            </legend>
            <div class="phrases-box">
                <?php echo $this->lists['searchphrase']; ?>
            </div>
            <div class="ordering-box">
                <label for="ordering" class="ordering">
                    <?php echo JText::_('COM_SEARCH_ORDERING'); ?>
                </label>
                <?php echo $this->lists['ordering']; ?>
            </div>
        </fieldset>
    <?php endif; ?>
    <?php if ($this->params->get('search_areas', 1)) : ?>
        <fieldset class="only">
            <legend>
                <?php echo JText::_('COM_SEARCH_SEARCH_ONLY'); ?>
            </legend>
            <?php foreach ($this->searchareas['search'] as $val => $txt) : ?>
                <?php $checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : ''; ?>
                <label for="area-<?php echo $val; ?>" class="checkbox">
                    <input type="checkbox" name="areas[]" value="<?php echo $val; ?>"
                           id="area-<?php echo $val; ?>" <?php echo $checked; ?> />
                    <?php echo JText::_($txt); ?>
                </label>
            <?php endforeach; ?>
        </fieldset>
    <?php endif; ?>
    <?php if ($this->total > 0) : ?>
        <div class="form-limit d-flex flex-wrap align-items-center">
            <div class="col-12 col-md-auto pl-0">
                <label for="limit">
                    <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
                </label>
            </div>
            <div class="col-12  col-md-auto px-0">
                <?php echo $this->pagination->getLimitBox(); ?>

            </div>

        </div>
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
    <?php endif; ?>
</form>
