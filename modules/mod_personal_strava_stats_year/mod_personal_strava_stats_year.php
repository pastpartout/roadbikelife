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
    'moving_time' => ['icon' => 'fa-clock'],
    'average_speed' => ['icon' => 'fa-tachometer'],
];

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__strava_member_stats_ytd'));
$query->where(array($db->quoteName('id') . ' = '.$db->quote(RoadbikelifeHelper::getParam('strava_profileId'))));
$db->setQuery($query);
$stravaStats =  $db->loadObject();

if ($stravaStats->distance > 0) {
    $stravaStats->average_speed = $stravaStats->distance / $stravaStats->moving_time;
}

require JModuleHelper::getLayoutPath('mod_personal_strava_stats_year', $params->get('layout', 'default'));