<?php

/**
 * @var $articles
 */

use Joomla\CMS\Factory;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

defined('_JEXEC') or die;
require_once JPATH_BASE.'/components/com_roadbikelife/models/resizeimage.php';
$imageModel = new RoadbikelifeModelResizeimage();

$app = Factory::getApplication();
$doc = $app->getDocument();
$sitename = $app->getConfig('sitename');
$wa    = $doc->getWebAssetManager();

$component     = Factory::getApplication()->bootComponent('com_fields');
$fieldModel = $component->getMVCFactory()->createModel('Field','Administrator');

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
	$article->introtext = short_str($article->introtext);
	if($fieldModel->getFieldValue(8, $article->id) == '1' && JFactory::getApplication()->input->get('show_unpublished','0') == '0' ) {
		unset($items[$key]);
	}
}

JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addScript('templates/custom/js/frontpage.js');
if(!$isSmartphone === true) {
	$doc->addScript('templates/custom/js/jquery.hoverdir.js');
	$doc->addScriptDeclaration("
	$(document).ready(function () {
	    $('.frontpage-grid article').each( function() { $(this).hoverdir(); } );
	});");
}
?>
<section class="frontpage-grid">
    <div class="row no-gutters">
        <div class="col-lg-4 introWrapper">
            <div class="intro">
                <div class="text ">
                    <div class="d-flex">
                        <a class="navbar-brand col-12 col-md-7 px-0" href="<?php echo Juri::base() ?>">
                            <?= file_get_contents(JPATH_BASE.'/templates/custom/img/logo_white.svg') ?>
                        </a>
                    </div>
                    <h1 class="mb-2 pt-0 mt-3 h5">
	                    <?= $this->params->get('intro_heading') ?>
                    </h1>
                    <div class="small">
	                    <?= $this->params->get('intro_text') ?>
                    </div>
                </div>
                <!--                <div class="text-center text-white">-->
                <!--                    --><?php //echo JLayoutHelper::render('joomla.blog.btn_strava_follow_club'); ?>
                <!--                </div>-->
            </div>
            <div class="tagsWrapper d-none d-lg-flex">
				<?php
                if(!$isSmartphone) {
	                jimport('joomla.application.module.helper');
	                $modules = JModuleHelper::getModules('hero-area');
	                foreach ($modules as $module) {
		                echo JModuleHelper::renderModule($module);
	                }
                }
				?>
            </div>
        </div>
        <div class="col-lg-8 leadingArticleWrapper">
			<?php
			$item = $items[0];
            if($item) {
	            $item->css_class = 'exclude-lazyload';
            }
			?>
			<?php echo JLayoutHelper::render('joomla.blog.post_item_fp', $item); ?>
        </div>
    </div>
    <div class="row no-gutters rowTwo">
        <div class="col-lg-6">
			<?php $item = $items[1]; ?>
			<?php echo JLayoutHelper::render('joomla.blog.post_item_fp', $item); ?>
        </div>
        <div class="col-lg-6">
			<?php $item = $items[2]; ?>
			<?php echo JLayoutHelper::render('joomla.blog.post_item_fp', $item); ?>
        </div>
    </div>
    <div class="row no-gutters rowThree">
		<?php $items = array_slice($items, 3); ?>

        <?php foreach($items as $item):?>
	        <?php $item->imgSize = 's'; ?>
            <div class="col-lg-4">
				<?php echo JLayoutHelper::render('joomla.blog.post_item_fp', $item); ?>
            </div>
		<?php endforeach ?>
    </div>
</section>
