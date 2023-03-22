<?php
defined('_JEXEC') or die;
require_once __DIR__.'/../helper/modBottomBarHelper.php';

//$appWeb = new JApplicationWeb;
//exit;
/** @var $contentAnchors */
/** @var $contentId */
/** @var $modulesToLoad */
/** @var $modulesCollected */
if (isset($contentId)) {
    $displayData = new stdClass();
    $displayData->id = $contentId;

}

$wa = \Joomla\CMS\Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseScript('offcanvas_js', JUri::base().'modules/mod_bottom_bar/assets/js/offcanvas.js');


?>
<div class="mobileBottomBarBackdrop modal-backdrop fade"></div>
<div class="mobileBottomBar">
    <nav>
        <div class="nav" id="nav-tab" role="tablist">
            <a class="nav-item nav-item-about d-flex"
               data-toggle="tab" href="#nav-about" role="tab"
               aria-controls="nav-tabContent">
                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 53.6 60"
                     viewBox="0 0 53.6 60"><path
                            d="M26.9.4 12.3 8.8c8.8 6 13.7 17.2 12.5 27.9A29.5 29.5 0 0 1 16 53.8l-5.5-3.2c4.2-3.7 7.5-8.6 8.2-13.7 1.4-9.6-3.4-20.5-12.4-24.6l-5.3 3v29.9l25.9 14.9 25.9-14.9V15.3L26.9.4zm14.3 16.3c-.3 1.8-1.9 5-2 7.4-.1 2.4.7 6.1-2.7 9.3-3.4 3.3-3.4 9.6-2.7 11.9l1.1 3.8c.2.6-.1 1.2-.4 1.4-.3.2-.6 0-.9-.8 0 0-4.1-7.1-3.2-12.4 1-5.3 4.8-4.8 5.6-8.2 1.1-4.8-2.3-6-3.5-5-1 .8-3.4 4.2-3.4 4.2-.2.3-.6.1-.6-.2-1-7.1-4.2-11.8-4.2-11.8-.3-.5.2-1 .7-.8.7.4 1.4.7 1.8.7 2.2.3 7.4 1.1 8.9-2 1.4-2.9 3-2.2 4.2-1.4 1.4.9 1.6 2.1 1.3 3.9z"
                            style="fill:#fff"/></svg>
            </a>
            <?php if (isset($contentAnchors)): ?>
                <?php
                foreach ($contentAnchors as $key => $contentAnchor): ?>
                    <a class="nav-item btnContentAnchor btn<?= $key ?>" href="#<?= $key ?>">
                        <i class="fal fa-fw <?= $contentAnchor['icon'] ?>"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php foreach ($modulesToLoad as $key => $moduleToLoad): ?>
                <a class="nav-item"
                   data-toggle="tab" href="#nav-<?= $key ?>" role="tab"
                   aria-controls="nav-tabContent">
                    <i class="fal fa-fw <?= $moduleToLoad['icon'] ?>"></i>
                </a>
            <?php endforeach; ?>
            <a class="nav-item mobileBottomBarToggle">
                <i class="fal fa-times fa-fw"></i>
            </a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <?php foreach ($modulesToLoad as $key => $moduleToLoad): ?>
            <div class="tab-pane fade" role="tabpanel"
                 id="nav-<?= $key ?>">
                <div class="h3">
                    <?= $modulesCollected[$key]['title'] ?>
                </div>
                <?= $modulesCollected[$key]['html'] ?>
            </div>
        <?php endforeach; ?>
        <div class="tab-pane fade article-modules" role="tabpanel"
             id="nav-about">
            <?php if(JUri::base() !== JUri::current()): ?>
                <a class="text-white btn-sm mb-3 text-center btn-home d-block" href="<?=JUri::base() ?>">
                    <i class="fa fa-home mr-2"></i>zur Startseite
                </a>
            <?php endif; ?>

            <?php $modulesToLoad = JModuleHelper::getModules('blog_sidebar_about'); ?>
            <?php foreach ($modulesToLoad as $key => $moduleToLoad): ?>
                <?php $moduleToLoadClone = modBottomBarHelper::setModuleParams($moduleToLoad); ?>
                        <?= JModuleHelper::renderModule($moduleToLoadClone) ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
