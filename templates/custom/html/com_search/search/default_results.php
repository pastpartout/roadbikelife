<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="row">
    <ol class="col-md-8 search-results list-unstyled <?php echo $this->pageclass_sfx; ?>">
        <?php foreach ($this->results as $result) : ?>
            <li class="result-title post postLink ">

                <a <?php if ($result->href) : ?>href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) : ?> target="_blank"<?php endif; ?>
                   class="postLinkItem hoverShadow">
                    <?php endif; ?>
                    <div class="h4">
                        <?php echo $this->pagination->limitstart + $result->count . '. '; ?>
                        <?php echo $result->title; ?>
                    </div>
                    <?php if ($result->section) : ?>
                        <div class="result-category mb-3">
                        <span class="small badge badge-primary <?php echo $this->pageclass_sfx; ?>">
                            <?php echo $this->escape($result->section); ?>
                        </span>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->params->get('show_date')) : ?>
                        <div class="date small result-created<?php echo $this->pageclass_sfx; ?>">
                            <i class="fal fa-calendar text-black-50 icon-margin-right"></i><?php echo JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created); ?>
                        </div>
                    <?php endif; ?>
                    <div class="result-text mb-3">
                        <?php echo $result->text; ?>
                    </div>
                    <?php if ($result->href) : ?>
                    <span class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-angle-right icon-margin-right"></i>
                    <?php echo JText::_('Weiterlesen'); ?>
                </span>
                </a>

                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
<div class="pagination">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>
