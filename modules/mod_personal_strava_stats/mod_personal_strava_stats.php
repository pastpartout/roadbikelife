<?php
/**
 * @copyright	Copyright © 2019 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');


$stravaFields = [
    'distance' => ['icon' => 'fa-arrows-h'],
    'elevation_gain' => ['icon' => 'fa-mountains'],
    'average_speed' => ['icon' => 'fa-tachometer'],
    'moving_time' => ['icon' => 'fa-clock'],
    'kudos_count' => ['icon' => 'fa-thumbs-up'],
    'count' => ['icon' => 'fa-redo'],
    'created' => [],
];

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select(implode(',',array_keys($stravaFields)));
$query->from($db->quoteName('#__strava_member_stats'));
$query->where(array($db->quoteName('id') . ' = '.$db->quote(RoadbikelifeHelper::getParam('strava_profileId'))));
$db->setQuery($query);
$stravaStats =  $db->loadObject();
$createdDate = $stravaStats->created;
unset($stravaStats->created);
require JModuleHelper::getLayoutPath('mod_personal_strava_stats', $params->get('layout', 'default'));
