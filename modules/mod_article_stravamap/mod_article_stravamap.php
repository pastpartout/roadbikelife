<?php
/**
 * @copyright    Copyright © 2019 - All rights reserved.
 * @license        GNU General Public License v2.0
 * @generator    http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

JLoader::register('modArticleStravaMap', __DIR__ . '/helper/mod_article_stravamap.php');
$modArticleStravaMap = new modArticleStravaMap($params);
$flotGraphs = $modArticleStravaMap->flotGraphs;
$stravaActivity = $modArticleStravaMap->getStravaActivity();

if (isset($stravaActivity->activity_json)) {
    $stravaActivities = $modArticleStravaMap->getStravaActivitiesforModule();
    $stravaActivitiesTotals = $modArticleStravaMap->getStravaActivitiesTotals();
    $modArticleStravaMap->addScripts();
    require JModuleHelper::getLayoutPath('mod_article_stravamap', $params->get('layout', 'default'));
}
