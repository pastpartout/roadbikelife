<?php
/**
 * @copyright	Copyright © 2019 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');


$app = Factory::getApplication();
$doc =$app->getDocument();
$id = $app->input->getCmd('id', 0);

if ($id !== 0) {
    $article = JTable::getInstance("content");
    $article->load($id);

	$fieldComponent     = $app->bootComponent('com_fields');
	$fieldModel = $fieldComponent->getMVCFactory()->createModel('Field','Administrator');
    $stravaActivityIds = $fieldModel->getFieldValue(5, $article->id);

	if(!is_array($stravaActivityIds)) {
		$stravaActivityIdsArray[0] = $stravaActivityIds;
		$stravaActivityIds = $stravaActivityIdsArray;
	}

}

if(count($stravaActivityIds) > 0) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('sas.activity_json');
    $query->from($db->quoteName('#__strava_activity_stats','sas'));
    $query->where(
    	$db->quoteName('sas.id') . ' IN ('.implode(',',$stravaActivityIds).')'
    );
    $db->setQuery($query);
	$stravaActivities = $db->loadObjectList();
	foreach ($stravaActivities as $stravaActivity) {
		$stravaActivitiesPepared[] = json_decode($stravaActivity->activity_json);
	}


	require JModuleHelper::getLayoutPath('mod_article_strava_stats', $params->get('layout', 'default'));

}

