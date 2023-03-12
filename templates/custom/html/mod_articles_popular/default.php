<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$config = JFactory::getConfig();

?>

<div class="latestPosts text-left">
    <ul class=" list-unstyled mb-0">
        <?php foreach ($list as $item) : ?>
            <?php $images = json_decode($item->images); ?>
            <li class="item mb-3 post postLink">
                <a href="<?php echo $item->link; ?>" itemprop="url" class="post postLinkItem">
                    <div class="d-flex">
                        <div class="col-2 pl-0">
                            <div class="item-image img-thumbnail shadow-sm post-image post-image-1-1 rounded-circle border-sm">
                                <div class="image-wrapper rounded-circle">
                                    <?= JLayoutHelper::render('joomla.blog.post_image_intro_small_circle', $images);?>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="article-date small">
                                <i class="fal fa-calendar icon-margin-right"></i><time datetime="<?php echo JHtml::_('date', $item->publish_up, 'c'); ?>"
                                      itemprop="datePublished"><?php echo JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC4')); ?></time>
                            </div>
                            <div class="text-left small">
                                <strong itemprop="name">
                                    <?php echo $item->title; ?>
                                </strong>
                            </div>
                        </div>
                    </div>

                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</div>
