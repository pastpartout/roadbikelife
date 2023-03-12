<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params = $this->item->params;
//$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
$fields  = $this->item->jcfields;
$app = Factory::getApplication();
$doc     = $app->getDocument();
$doc->addScript("templates/custom/js/articles.js");

JLoader::register('RoadbikelifeModelLike', 'components/com_roadbikelife/models/like.php');
$RoadbikelifeModelLike = new RoadbikelifeModelLike;
$likesCount            = $RoadbikelifeModelLike->getLikesCount($this->item->id, 'content');

$likesDisabled         = $RoadbikelifeModelLike->getLikesIsDisabled($this->item->id, 'content');
$isSmartphone = ($app->client->mobile === true);


$stravaActivityId = (int) $fields['5']->rawvalue;
JFactory::getDocument()->addScriptDeclaration('
function PopupCenter(pageURL, title, w, h) {
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var targetWin = window.open(pageURL, title, \'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=\' + w + \', height=\' + h + \', top=\' + top + \', left=\' + left);
    return targetWin;
}
');
?>
<div class="item-page <?php echo $this->pageclass_sfx; ?> <?php if ($stravaActivityId > 0): ?>stravaActivityPost<?php endif ?>">
    <div class="row no-gutters">
        <div class="articleCol <?php if ($stravaActivityId > 0): ?>col-lg-7 hasSidebar<?php endif ?>">
            <div class="row">
                <div class="col-auto col-md-3 mb-3 mb-md-0">
					<?= JLayoutHelper::render('joomla.blog.btn_like', $this->item); ?>
                </div>
                <div class="col-auto col-md-9">
					<?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
						<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags_rbl'); ?>
						<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
					<?php endif; ?>
                </div>
            </div>
            <div class="py-3">
                <div class="small">
					<?php if (!isset($params) || $params->get('show_publish_date')) : ?>
                        <i class="fal fa-calendar text-black-50 icon-margin-right"></i>
                        <time datetime="
            <?php echo JHtml::_('date', $this->item->publish_up, 'c'); ?>" itemprop="datePublished">
							<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC4'))); ?>
                        </time>
					<?php endif; ?>
                </div>
            </div>

            <h2>
				<?= $fields['2']->value; ?>
            </h2>


			<?php if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
			<?php echo $this->item->event->beforeDisplayContent; ?>

			<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
				|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
				<?php echo $this->loadTemplate('links'); ?>
			<?php endif; ?>
			<?php if ($params->get('access-view')): ?>

				<?php
				if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative):
					echo $this->item->pagination;
				endif;
				?>
				<?php if (isset ($this->item->toc)) :
					echo $this->item->toc;
				endif; ?>


                <div>
					<?php echo $this->item->text; ?>
                </div>

				<?php
				if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative):
					echo $this->item->pagination;
					?>
				<?php endif; ?>
				<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
					<?php echo $this->loadTemplate('links'); ?>
				<?php endif; ?>
				<?php // Optional teaser intro text for guests ?>
			<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
				<?php echo $this->item->introtext; ?>
				<?php // Optional link to let them register to see the whole article. ?>
				<?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
					<?php $menu = $app->getMenu(); ?>
					<?php $active = $menu->getActive(); ?>
					<?php $itemId = $active->id; ?>
					<?php $link = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
					<?php $link->setVar('return', base64_encode(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language), false))); ?>
                    <p class="readmore">
                        <a href="<?php echo $link; ?>" class="register">
							<?php $attribs = json_decode($this->item->attribs); ?>
							<?php
							if ($attribs->alternative_readmore == null) :
								echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
                            elseif ($readmore = $this->item->alternative_readmore) :
								echo $readmore;
								if ($params->get('show_readmore_title', 0) != 0) :
									echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
								endif;
                            elseif ($params->get('show_readmore_title', 0) == 0) :
								echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
							else :
								echo JText::_('COM_CONTENT_READ_MORE');
								echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
							endif; ?>
                        </a>
                    </p>
				<?php endif; ?>
			<?php endif; ?>


			<?php
			$shareurl = JUri::current();
			$shareurl = urlencode($shareurl);
			?>
            <div class="social-sharing py-3 text-center clearfix">
                <p class="small text-black-50">
                    Like and Share...
                </p>
                <ul class="list-unstyled mb-0 row justify-content-center">
                    <li class="col-2">
                        <a class="no-hover"
                           onclick="PopupCenter('https://www.facebook.com/sharer/sharer.php?u=<?php echo $shareurl ?>','<?php echo JText::_('POST_TO_FACEBOOK') ?>','600','650')">
                            <i class="text-gradient fab fa-2x fa-fw fa-facebook"></i>
                        </a>
                    </li>
                    <li class="col-2">
                        <a class="no-hover"
                           onclick="PopupCenter('https://twitter.com/home?status=<?php echo $shareurl ?>','<?php echo JText::_('POST_TO_TWITTER') ?>','500','600')">
                            <i class="text-gradient fab fa-2x fa-fw fa-twitter-square"></i>
                        </a>
                    </li>
                </ul>
            </div>

			<?php echo $this->item->event->afterDisplayContent; ?>
            <div class="d-none d-lg-block">
				<?php
				if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
					echo $this->item->pagination;
					?>
				<?php endif; ?>

            </div>
        </div>
		<?php if ($stravaActivityId > 0): ?>
            <aside class="col-lg-5 px-0">
				<?php
				jimport('joomla.application.module.helper');

				$modules = JModuleHelper::getModules('article_sidebar');
				foreach ($modules as $module)
				{
					echo JModuleHelper::renderModule($module);
				}
				?>
            </aside>
		<?php endif ?>
        <div class="col-12 d-block d-lg-none mobilePageNav">
			<?php
			if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
				echo $this->item->pagination;
				?>
			<?php endif; ?>
            <!--                after content -->

        </div>
    </div>


    <meta itemprop="inLanguage"
          content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>"/>
	<?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
        </div>
	<?php endif;
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}
	?>

</div>
<?php
jimport('joomla.application.module.helper');

$modules = JModuleHelper::getModules('article_bottom');
foreach ($modules as $module)
{
	echo JModuleHelper::renderModule($module);
}
?>
