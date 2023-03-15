<?php
/**
 * @copyright      Copyright © 2019 - All rights reserved.
 * @license        GNU General Public License v2.0
 * @generator    http://xdsoft/joomla-module-generator/
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$app = Factory::getApplication();
$doc = $app->getDocument();
$input = $app->input;
$view = $input->getCmd('view', '');
$menu = $app->getMenu();


$modulesToLoad = [
    'tags_popular' => [
        'icon' => 'fa-tag'
    ],
    'search' => [
        'icon' => 'fa fa-search'
    ],


];
if (
    $view == 'article'
) {
    $app = Factory::getApplication();
    $contentId = $app->input->getCmd('id', 0);
    JLoader::register('RoadbikelifeModelLike', 'components/com_roadbikelife/models/like.php');
    $RoadbikelifeModelLike = new RoadbikelifeModelLike;
    $likesDisabled = $RoadbikelifeModelLike->getLikesIsDisabled($contentId, 'content');

    $contentComponent = $app->bootComponent('com_content');
    $cmodel = $contentComponent->getMVCFactory()->createModel('Article', 'Site');
    $catid = $cmodel->getItem($contentId)->catid;


    if ($catid === (int)$params->get('category_id', 0)) {
        $fieldComponent = $app->bootComponent('com_fields');
        $fieldModel = $fieldComponent->getMVCFactory()->createModel('Field', 'Administrator');

        $stravaActivityId = $fieldModel->getFieldValue(5, $contentId);

        $fieldComponent = $app->bootComponent('com_fields');
        $fieldModel = $fieldComponent->getMVCFactory()->createModel('Field', 'Administrator');
        $galleryId = $fieldModel->getFieldValue(3, $contentId);

        $contentAnchors = [
            'content-ccomment' => [
                'icon' => 'fa-comments'
            ],
        ];

        if ((int)$galleryId != 2) {
            $contentAnchors['content-images'] = ['icon' => 'fa-camera'];
        }

        if ((int)$stravaActivityId > 0) {
            $contentAnchors['content-gpx'] = ['icon' => 'fa-map'];
            $contentAnchors['content-strava'] = ['icon' => 'fab fa-strava'];
        }
    }

}

foreach ($modulesToLoad as $key => $moduleToLoad) {
    jimport('joomla.application.module.helper');
    $module = JModuleHelper::getModule($key);
    $modulesCollected[$key]['html'] = JModuleHelper::renderModule($module);
    $modulesCollected[$key]['title'] = $module->title;
}

require JModuleHelper::getLayoutPath('mod_bottom_bar', $params->get('layout', 'default'));
