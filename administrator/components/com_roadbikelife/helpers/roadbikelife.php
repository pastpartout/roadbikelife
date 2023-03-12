<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
JLoader::register('MainModelMain', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jotcache' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'main.php');
JLoader::register('MainModelRecache', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jotcache' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'recache.php');

/**
 * Roadbikelife helper.
 *
 * @since  1.6
 */
class RoadbikelifeHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
				JHtmlSidebar::addEntry(
			JText::_('COM_ROADBIKELIFE_TITLE_APIUPDATES'),
			'index.php?option=com_roadbikelife&view=apiupdates',
			$vName == 'apiupdates'
		);
	}



	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = Factory::getUser();
		$result = new JObject;

		$assetName = 'com_roadbikelife';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

    public static function getParam($name) {
	    $params = JComponentHelper::getParams('com_roadbikelife');
        return $params->get($name);
    }

    public static function formatRenderedValue($value, $property = null, $withUnits = true, $forMember = false, $noHtml = false)
    {
        if ((string)$value != '') {
            $precision = 0;
            $unit = '';
            $decSeperator = ',';
            $kSeperator = '.';

            $lang = JFactory::getLanguage();
            $langTag = $lang->getTag();
            if($langTag != 'de-DE') {
                $decSeperator = '.';
            }

            switch ($property) {
                case "created":
                    $value = strtotime($value);
                    break;
                case "kudos_count":
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "achievement_count":
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "achievement_count":
                    if ($forMember == true) $unit = '';
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "average_temp":
                    if ($forMember == true)  $unit = JText::_('DEGREES');
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "athlete_count":
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "average_heartrate":
                    $unit = 'BPM';
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "weighted_average_watts":
                    if ($forMember == true) $unit = 'Watt';
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "max_speed":
                case "average_speed":
                    $unit = 'km/h';
                    $value = number_format($value * 3.6, 1, $decSeperator, $kSeperator);
                    break;
                case "count":
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "cadence":
                case "max_cadence":
                    $unit = 'U/min';
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "max_watts":
	            case "watts":
		            $unit = 'Watt';
		            $value = number_format($value, 0, $decSeperator, $kSeperator);
		            break;
	            case "average_watts":
		            $unit = 'Watt<span class="ml-1">&Oslash;</span>';
		            $value = number_format($value, 0, $decSeperator, $kSeperator);
		            break;
                case "grade_smooth":
                    $unit = '%';
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
                case "elevation_gain":
                case "total_elevation_gain":
                    $unit = 'm';
                    $value = number_format($value, 0, $decSeperator, $kSeperator);
                    break;
	            case "distance":
		            $value = $value / 1000;
		            $value = number_format($value, 0, $decSeperator, $kSeperator);
		            $unit = JTEXT::_('DISTANCE_SUM');
		            if ($forMember == true) $unit = 'km';
		            $precision = 1;
		            break;
	            case "distance_m":
		            $value = $value;
		            $value = number_format($value, 0, $decSeperator, $kSeperator);
		            $unit = JTEXT::_('DISTANCE_SUM');
		            if ($forMember == true) $unit = 'm';
		            $precision = 1;
		            break;
	            case "calories":
		            $value = round($value,0);
		            $value = number_format($value, 0, $decSeperator, $kSeperator);
		            $value = $value.'&nbsp;'.JTEXT::_('KCALORIES_SUM');

		            break;
	            case "moving_time" :
	            case "elapsed_time":
		            $init = $value;
		            $hours = floor($init / 3600);
		            $minutes = floor(($init / 60) % 60);
		            $seconds = $init % 60;
		            $value = $hours . 'h '.$minutes.'min ';

		            break;
	            case "moving_time_s" :
		            $init = $value;
		            $hours = floor($init / 3600);
		            $minutes = floor(($init / 60) % 60);
		            $seconds = $init % 60;
		            $value = $minutes . 'min '.$seconds.'s ';

		            break;

            }



            if ($withUnits == true && $unit != '') {
                if ($noHtml == true) {
                    return $value . ' ' . JText::_($unit);
                } else {
                    return $value . ' ' . JText::_($unit);
                }
            } else {
                return $value;
            }

        } else {
            return JText::_('NO_DATA');
        }
    }

    public static function deleteCache($comViewsToDelete,$run = true)
    {
        $jotcache = new MainModelMain();
        $jotrecache = new MainModelRecache();
        $db = JFactory::getDbo();
        $tableName = '#__jotcache';
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($tableName));
        $db->setQuery($query);
        $items = $db->loadObjectList();

        foreach ($items as $key => $item) {
            $com = $item->com;
            $view = $item->view;
            $comView = "$com.$view";

            foreach ($comViewsToDelete as $comViewToDelete => $idsToDelete) {

            if($comView == $comViewToDelete) {
	            if(is_array($idsToDelete)) {
	            	foreach ($idsToDelete as $idToDelete) {
			            if($item->id === $idToDelete) {
				            $fNamesToDelete[] = $item->fname;
			            }
		            }

	            } else {
		            $fNamesToDelete[] = $item->fname;
	            }

            }
            }
        }
        $app = JFactory::getApplication();
        $input = $app->input;
        $input->set('scope', 'chck');
        $input->set('jotcacheplugin', 'recache');

        if ($fNamesToDelete && count($fNamesToDelete) > 0) {
            $jotrecache->flagRecache($fNamesToDelete);
        }

        if($run === true) {
	        $jotrecache->runRecache();
	        $jotrecache->controlRecache(0);
        }

    }

}

