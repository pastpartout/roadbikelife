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
<div class="container">
    <div class="item-search <?php echo $this->pageclass_sfx; ?>">
        <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header mb-5">
            <h1>
                <?php if ($this->escape($this->params->get('page_heading'))) : ?>
                    <?php echo $this->escape($this->params->get('page_heading')); ?>
                <?php else : ?>
                    <?php echo $this->escape($this->params->get('page_title')); ?>
                <?php endif; ?>
            </h1>
        </div>
        <?php endif; ?>
        <?php echo $this->loadTemplate('form'); ?>
        <?php if ($this->error == null && count($this->results) > 0) : ?>
            <?php echo $this->loadTemplate('results'); ?>
        <?php else : ?>
            <?php echo $this->loadTemplate('error'); ?>
        <?php endif; ?>
    </div>
</div>
