<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="tagsSimilar <?php echo $moduleclass_sfx; ?>">
    <div class="container-fluid">
        <?php if ($module->showtitle): ?>
            <h3 class="rblHeadline">
            <span>
                  <?= $module->title; ?>
            </span>
            </h3>
        <?php endif; ?>
        <div class="tagssimilar row">
            <?php if ($list) : ?>
                <?php foreach ($list as $i => $item) : ?>
                    <?php $images = json_decode($item->images); ?>
                    <?php $item->introtext = substr($item->introtext, 0, strpos(wordwrap($item->introtext, '250'), "\n")); ?>
                    <div class="col-md-6 col-lg-3 post  postLink" href="<?php echo JRoute::_($item->link); ?>">
                        <a class="postLinkItem boxed boxed-dark" href="<?php echo JRoute::_($item->link); ?>">
                            <div class="item-image mb-3 post-image post-image-16-9">
                                <div class="image-wrapper">
                                    <?= JLayoutHelper::render('joomla.blog.post_image_intro_medium', $item);?>
                                </div>
                            </div>
                            <div class="inner">
                                <h3 itemprop="name" class="h5">
                                    <?php echo $item->core_title; ?>
                                </h3>
                                <div class="small postLinkText">
                                    <?php echo $item->introtext_short; ?>
                                </div>
                                <div class="mt-3 btn  btn-sm btnReadMore">
                                    <i class="fa fa-angle-right icon-margin-right"></i>
                                    <?php echo JText::_('READ_MORE'); ?>
                                </div>
                            </div>
                        </a>

                    </div>

                <?php endforeach; ?>

            <?php else : ?>
                <span><?php echo JText::_('MOD_TAGS_SIMILAR_NO_MATCHING_TAGS'); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
