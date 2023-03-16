<?php

/**
 * @var $articles
 */

use Joomla\CMS\Factory;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

defined('_JEXEC') or die;
require_once JPATH_BASE . '/components/com_roadbikelife/models/resizeimage.php';
$imageModel = new RoadbikelifeModelResizeimage();

JHtml::_('jquery.framework');
$app = Factory::getApplication();
$doc = $app->getDocument();
$wa = $doc->getWebAssetManager();
$doc = JFactory::getDocument();
$wa->registerAndUseScript('rbl_frontpage','templates/custom/js/frontpage.js');

$fieldModel = Factory::getApplication()->bootComponent('com_fields')->getMVCFactory()->createModel('Field', 'Administrator');
$items = $this->items;
$isSmartphone = ($app->client->mobile === true);

function short_str($str, $max = 350)
{
    $str = trim(strip_tags($str));
    if (strlen($str) > $max) {
        $s_pos = strpos($str, ' ');
        $cut = $s_pos === false || $s_pos > $max;
        $str = wordwrap($str, $max, ';;', $cut);
        $str = explode(';;', $str);
        $str = $str[0] . '...';
    }
    return $str;
}

foreach ($items as $key => $article) {
    $article->introtext = short_str($article->introtext, 300);
    if ($fieldModel->getFieldValue(8, $article->id) == '1' && JFactory::getApplication()->input->get('show_unpublished', '0') == '0') {
        unset($items[$key]);
    }
}

?>
<section class="frontpage-grid">
    <div class="intro">
        <div class="text ">
            <div class="logo-wrapper">
                <a class="navbar-brand" href="<?php echo Juri::base() ?>">
                    <?= file_get_contents(JPATH_BASE . '/templates/custom/img/logo_white.svg') ?>
                </a>
            </div>
            <h1 class="h2 mb-2 font-weight-light">
                <?= $this->params->get('intro_heading') ?>
            </h1>
            <div class="small collapse">
                <?= $this->params->get('intro_text') ?>
            </div>
        </div>
    </div>
    <div class="frontpage-grid-items">
        <?php if (count($items) > 0): ?>
            <?php foreach ($items as $key => $item): ?>
                <div class="frontpage-grid-item  <?php if ($key === 0): ?>active<?php endif ?>  <?php if ($key+1 === count($items)): ?>last<?php endif ?> <?php if ($key === 0): ?>first<?php endif ?>"
                     id="item-<?= $item->id ?>" data-sectionid="<?= $item->id ?>">
                    <?php echo JLayoutHelper::render('rbl.blog.post_item_fp', ['item' => $item]); ?>
                    <?php if ($this->pagination->pagesCurrent < $this->pagination->pagesTotal): ?>
                        <?php echo JLayoutHelper::render('rbl.blog.post_item_fp_next', ['nextItem' => $items[$key + 1]]); ?>
                    <?php endif ?>
                    <?php if ($key === 0): ?>
                        <a class="btn btn-next-item d-flex d-lg-none fade" style="line-height: 1">
                            <i class="fal fa-2x fa-angle-right fa-fw"></i>
                        </a>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <?php  echo $this->pagination->getPaginationLinks('rbl.pagination.frontpage', ['items' => $items]);  ?>
</section>
<footer>
    <?php $metamenuModule = JModuleHelper::getModuleById('95'); ?>
    <?= JModuleHelper::renderModule($metamenuModule) ?>
</footer>