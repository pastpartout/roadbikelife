<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

use Joomla\CMS\Factory;

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelApiupdatewheather extends RoadbikelifeModelRoadbikelife
{


	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */

	private $apiKey;
	private $stravaActivities;
	private array $stravaActivitiesIds;

	public function __construct($config = [])
	{
		parent::__construct($config);

	}


	public function update()
	{
		$stravaAcitivties = $this->getStravaActivites();
		$apiKey           = RoadbikelifeHelper::getParam('darkskyweather_apiKey');
		if (isset($stravaAcitivties) && count($stravaAcitivties) > 0)
		{

			foreach ($stravaAcitivties as $stravaAcitivty)
			{

				$id                   = $stravaAcitivty->id;
				$timeStream           = json_decode($stravaAcitivty->time_json);
				$activity             = json_decode($stravaAcitivty->activity_json);
				$activity->start_date = strtotime($activity->start_date);
				$activityLatLng       = json_decode($stravaAcitivty->latlng_json);
				$wheatherData         = [];				$curl                 = curl_init();


                foreach ($activityLatLng as $key => $latlng)
				{
					if ($key % 200 == 0)
					{
						$time = $timeStream[$key] + $activity->start_date;
						curl_setopt_array($curl, [
							CURLOPT_URL            => "https://api.darksky.net/forecast/$apiKey/$latlng[0],$latlng[1],$time?lang=de&units=ca",
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_ENCODING       => "",
							CURLOPT_MAXREDIRS      => 10,
							CURLOPT_TIMEOUT        => 30,
							CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST  => "GET",
							CURLOPT_SSL_VERIFYPEER => false

						]);

						$response = json_decode(curl_exec($curl));
						$err      = curl_error($curl);

						if ($err)
						{
							echo "cURL Error #:" . $err;
							exit();
						}

						$wheatherData[] = [
							'icon'        => $response->currently->icon,
							'windSpeed'   => $response->currently->windSpeed,
							'windBearing' => $response->currently->windBearing,
						];
					}
				}


				if (isset($response) && $response !== false)
				{
					// Save
					$tableName             = '#__strava_activity_wheather';
					$fields                = new stdClass();
					$fields->id            = $id;
					$fields->wheather_json = json_encode($wheatherData);
					$fields->created       = date("Y-m-d H:i:s");

					$db = JFactory::getDbo();
					try
					{
						$query  = $db->getQuery(true);
						$result = JFactory::getDbo()->insertObject($tableName, $fields);
					}
					catch (Exception $e)
					{
						$result = JFactory::getDbo()->updateObject($tableName, $fields, 'id');
					}
				}
			}
		}

	}


	private function getStravaActivites()
	{
		if (!isset($this->stravaActivities))
		{
			// get Strava Acitivities
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__strava_activity_stats'));
			if (isset($this->stravaActivitiesIds))
			{
				$query->where($db->quoteName('id') . ' IN (' . implode(',', $this->stravaActivitiesIds) . ')');
			}

			$query->order('created DESC');
			$db->setQuery($query);
			$this->stravaActivities = $db->loadObjectList();
		}

		return $this->stravaActivities;
	}


	/**
	 * @param   array  $stravaActivitiesIds
	 */
	public function setStravaActivitiesIds($stravaActivitiesIds): void
	{
		$this->stravaActivitiesIds = $stravaActivitiesIds;
	}


	private function throwErrors($response)
	{
		if (isset($response->errors))
		{
			echo json_encode($response);
			exit;
		}
	}
}
