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


jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');
JLoader::register('RoadbikelifeModelApiupdatewheather', __DIR__ . '/apiupdatewheather.php');
JLoader::register('StravaApi', JPATH_ROOT . '/components/com_roadbikelife/models/includes/StravaApi.php');
JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelApiupdatestrava extends RoadbikelifeModelRoadbikelife
{


	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public $streamFields = ['altitude', 'distance', 'watts', 'heartrate', 'temp', 'cadence', 'grade_smooth', 'latlng', 'time'];
	private $allowedActivityTypes = ['Ride', 'VirtualRide'];
	private $mkDirMode = 0775;
    public StravaApi $stravaApi;
	private $stravaActivities;
	private $userIdToUpdate = 646;
	private $accessToken;

	public function __construct($config = [])
	{

		parent::__construct($config);

	}

	public function setStravaApi()
	{
		if (!isset($this->stravaApi))
		{
			$stravaApi       = new StravaApi (
				RoadbikelifeHelper::getParam('strava_clientId'),
				RoadbikelifeHelper::getParam('strava_clientSecret')
			);
			$this->stravaApi = $stravaApi;
		}
	}

	public function athenticate()
	{
		//update dates
		$db        = JFactory::getDbo();
		$tableName = '#__strava_member_tokens';
		$query     = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName($tableName));
		$query->where($db->quoteName('user_id') . ' = ' . $db->quote($this->userIdToUpdate));
		$db->setQuery($query);
		$this->accessToken = $db->loadObject();
		$this->refreshToken();
	}

	private function refreshToken()
	{
		$db        = JFactory::getDbo();
		$athleteId = $this->accessToken->id;
		$api       = $this->stravaApi;
		$today     = date('d.m.Y H:i:s');

		if (
			strtotime($today) > strtotime($this->accessToken->created . ' + 30 minutes')
		)
		{
			if ($this->accessToken->refresh_token == '')
			{
				$newToken = $api->refreshToken($this->accessToken->token);
			}
			else
			{
				$newToken = $api->refreshToken($this->accessToken->refresh_token);
			}

			$this->throwErrors($newToken);

			if ($newToken->access_token != '' && $newToken->refresh_token != '')
			{
				$query      = $db->getQuery(true);
				$conditions = [
					$db->quoteName('id') . ' = ' . $db->quote($athleteId),
				];
				$fields     = [
					$db->quoteName('token') . ' = ' . $db->quote($newToken->access_token),
					$db->quoteName('refresh_token') . ' = ' . $db->quote($newToken->refresh_token),
					$db->quoteName('created') . ' = NOW()'
				];
				$tableName  = '#__strava_member_tokens';
				$query->update($db->quoteName($tableName))->set($fields)->where($conditions);
				$db->setQuery($query);
				$db->execute();

				$this->accessToken->access_token  = $newToken->access_token;
				$this->accessToken->refresh_token = $newToken->refresh_token;
			}
		}
		else
		{
			$api->setAccessToken($this->accessToken->token);
		}

	}

	private function upateAthleteStats()
	{
		$today    = date('d.m.Y H:i:s');
		$datePast = $today . ' - 7 days ';

		$api       = $this->stravaApi;
		$athleteId = $this->accessToken->id;
		$activites = $api->get('athlete/activities', ['per_page' => '200', [], 'after' => strtotime($datePast), 'before' => strtotime($today)]);
		$this->throwErrors($activites);

		//calc
		$distance        = $movingTime = $kudosCount = $prCount = $achievementsCount = $elevationGain = $averageSpeed = $activityCounter = 0;
		$collectedPhotos = [];

		foreach ($activites as $i => $activity)
		{
			if (in_array($activity->type, $this->allowedActivityTypes))
			{
				$distance          += $activity->distance;
				$movingTime        += $activity->moving_time;
				$kudosCount        += $activity->kudos_count;
				$prCount           += $activity->pr_count;
				$achievementsCount += $activity->achievement_count;
				$elevationGain     += $activity->total_elevation_gain;
				$activityCounter++;
			}
		}
		$collectedPhotosJson = json_encode($collectedPhotos);
		if ($movingTime > 0)
		{
			$averageSpeed = $distance / $movingTime;
		}


		// Save
		$tableName                 = '#__strava_member_stats';
		$fields                    = new stdClass();
		$fields->id                = $athleteId;
		$fields->count             = $activityCounter;
		$fields->distance          = $distance;
		$fields->moving_time       = $movingTime;
		$fields->kudos_count       = $kudosCount;
		$fields->pr_count          = $prCount;
		$fields->achievement_count = $achievementsCount;
		$fields->elevation_gain    = $elevationGain;
		$fields->average_speed     = $averageSpeed;
		$fields->photos            = $collectedPhotosJson;
		$fields->created           = date("Y-m-d H:i:s");
		$db                        = JFactory::getDbo();
		try
		{
			$query  = $db->getQuery(true);
			$result = JFactory::getDbo()->insertObject($tableName, $fields);
		}
		catch (Exception $e)
		{
			$result = JFactory::getDbo()->updateObject($tableName, $fields, 'id');
		}

		// save stats ytd
		// get stats
		$athleteStats = $api->get('athletes/' . $athleteId . '/stats');
		$this->throwErrors($athleteStats);
		$athleteStats = $athleteStats->ytd_ride_totals;

		$tableName              = '#__strava_member_stats_ytd';
		$fields                 = new stdClass();
		$fields->id             = $athleteId;
		$fields->count          = $athleteStats->count;
		$fields->distance       = $athleteStats->distance;
		$fields->moving_time    = $athleteStats->moving_time;
		$fields->elevation_gain = $athleteStats->elevation_gain;
		$fields->created        = date("Y-m-d H:i:s");
		$db                     = JFactory::getDbo();
		try
		{
			$query = $db->getQuery(true);
			JFactory::getDbo()->insertObject($tableName, $fields);
		}
		catch (Exception $e)
		{
			JFactory::getDbo()->updateObject($tableName, $fields, 'id');
		}
	}

	private function getDistanceMarkerData($distanceData, $latlngData, $stravaActivity, $timeData)
	{
		foreach ($distanceData as $key => $distance)
		{
			if (isset($distanceData[$key + 1]))
			{
				$time          = $timeData[$key + 1] - $timeData[$key];
				$difference    = ($distanceData[$key + 1] - $distance) / $time;
				$differences[] = $difference;
			}
		}

		$coords = $latlngData;
		arsort($differences, SORT_NUMERIC);
		$shortestDifferenceCoord = $coords[array_key_first($differences)];

		return [
			'max_latlng' => $shortestDifferenceCoord,
			'max_value'  => RoadbikelifeHelper::formatRenderedValue($stravaActivity->max_speed, 'max_speed', true)
		];
	}

	private function getGrade_smoothMarkerData($gradeData, $latlngData, $stravaActivity)
	{
		arsort($gradeData, SORT_NUMERIC);
		$coords             = $latlngData;
		$HighestGradeCoords = $coords[array_key_first($gradeData)];
		rsort($gradeData, SORT_NUMERIC);

		return [
			'max_latlng' => $HighestGradeCoords,
			'max_value'  => RoadbikelifeHelper::formatRenderedValue($gradeData[0], 'grade_smooth', true)
		];
	}

	private function getCadenceMarkerData($cadenceData, $latlngData, $stravaActivity)
	{
		if (isset($stravaActivity->average_cadence))
		{
			arsort($cadenceData, SORT_NUMERIC);
			$coords               = $latlngData;
			$HighestCadenceCoords = $coords[array_key_first($cadenceData)];
			rsort($cadenceData, SORT_NUMERIC);

			return [
				'max_latlng' => $HighestCadenceCoords,
				'max_value'  => RoadbikelifeHelper::formatRenderedValue($cadenceData[0], 'cadence', true)
			];
		}
	}


	private function getWattsMarkerData($wattsData, $latlngData, $stravaActivity)
	{
		if ($stravaActivity->device_watts === true)
		{

			arsort($wattsData, SORT_NUMERIC);
			$coords             = $latlngData;
			$HighestWattsCoords = $coords[array_key_first($wattsData)];
			rsort($wattsData, SORT_NUMERIC);

			return [
				'max_latlng' => $HighestWattsCoords,
				'max_value'  => RoadbikelifeHelper::formatRenderedValue($wattsData[0], 'watts', true)
			];
		}
	}


	private function getTimeMarkerData($hrData, $latlngData, $stravaActivity)
	{
		return;
	}

	private function getTempMarkerData($hrData, $latlngData, $stravaActivity)
	{
		return;
	}

	private function getHeartrateMarkerData($hrData, $latlngData, $stravaActivity)
	{
		if ($stravaActivity->has_heartrate === true)
		{
			arsort($hrData, SORT_NUMERIC);
			$coords          = $latlngData;
			$HighestHrCoords = $coords[array_key_first($hrData)];

			rsort($hrData, SORT_NUMERIC);

			return [
				'max_latlng' => $HighestHrCoords,
				'max_value'  => RoadbikelifeHelper::formatRenderedValue($stravaActivity->max_heartrate, 'average_heartrate', true)
			];
		}

	}

	private function getAltitudeMarkerData($altitudeData, $latlngData, $stravaActivity)
	{
		arsort($altitudeData, SORT_NUMERIC);
		$coords                 = $latlngData;
		$HighestElevationCoords = $coords[array_key_first($altitudeData)];
		rsort($altitudeData, SORT_NUMERIC);

		return [
			'max_latlng' => $HighestElevationCoords,
			'max_value'  => RoadbikelifeHelper::formatRenderedValue($altitudeData[0], 'elevation_gain', true)
		];
	}

	private function getLatestActivites($limit)
	{
		$api       = $this->stravaApi;
		$activites = $api->get('athlete/activities', ['per_page' => $limit, [], 'before' => strtotime('now')]);

		return $activites;
	}

	public function getStravaActivityFieldOption($ids)
	{
		$this->setStravaApi();
		$this->athenticate();
		$this->refreshToken();
		$options = [];
		if(!is_array($ids)) {
			$ids[0] = $ids;
		}
		foreach ($ids as $id) {
			$activitity = $this->stravaApi->get("activities/$id");
			$options[]     = [
				'value' => $activitity->id,
				'text'  => $activitity->name,
			];
		}


		return $options;
	}

	public function getStravaActivityFieldOptions($limit)
	{
		$this->setStravaApi();
		$this->athenticate();
		$this->refreshToken();
		$activitities = $this->getLatestActivites($limit);
		foreach ($activitities as $activitity)
		{
			$options[] = [
				'value' => $activitity->id,
				'text'  => $activitity->name,
			];
		}


		return $options;
	}


	public function update()
	{

		$this->setStravaApi();
		$this->athenticate();

		if (isset($this->accessToken))
		{
			$this->upateAthleteStats();
			$this->getStravaActivities();

			if (isset($this->stravaActivities) && count($this->stravaActivities) > 0)
			{
				$this->updateStravaActivites();
				$this->updateStravaActivitesWheather();
				$this->controlCache();
			}

			return $this->stravaActivities;

		}
		else
		{
			exit ('no Token received. Strava User is not Valid?!');
		}

	}

	private function controlCache()
	{
		if (count($this->stravaActivities) > 0)
		{
			$contentIds = [];
			foreach ($this->stravaActivities as $stravaActivity)
			{
				$contentIds[] = $stravaActivity->item_id;
			}
			$contentIds = array_unique($contentIds);

			$user   = JFactory::getUser();
			$isroot = $user->authorise('core.login.admin');
			if (!$isroot)
			{
				RoadbikelifeHelper::deleteCache([
					'com_content.article'  => $contentIds,
					'com_content.category' => '*',
				], true);
			}
		}

	}

	private function getMarkerData($stravaActivityStream, $stravaActivity)
	{
		foreach ($this->streamFields as $streamField)
		{
			if ($streamField != 'latlng' && isset($stravaActivityStream[$streamField]))
			{
				$functionName             = "get" . ucfirst($streamField) . 'MarkerData';
				$markerData[$streamField] = $this->{$functionName}($stravaActivityStream[$streamField], $stravaActivityStream['latlng'], $stravaActivity, $stravaActivityStream['time']);
			}
		}

		return $markerData;
	}

	private function combineStravaPhotos($photos, $photosXL)
	{
		foreach ($photosXL as $key => $photoXL)
		{
			if (isset($photoXL->urls->{1000}))
			{
				$photos[$key]->urls->{1000} = $photoXL->urls->{1000};
			}
		}

		return $photos;
	}

	private function getSegmentsData($segmentEfforts)
	{
		$segmentsData = [];
		foreach ($segmentEfforts as $segmentEffort)
		{
			if ($segmentEffort->achievements[0]->type === 'overall' && $segmentEffort->achievements[0]->rank <= 10)
			{
				$segment                     = $this->stravaApi->get("segments/" . $segmentEffort->segment->id);
				$segmentEffort->segment->map = $segment->map;
				$segmentsData[]              = $segmentEffort;
			}
			else
			{
				unset($segmentEffort);
			}
		}

		return $segmentsData;
	}

	private function updateStravaActivitesWheather()
	{
		$stravaAcitivityIds = [];
		foreach ($this->stravaActivities as $stravaActivity)
		{
			$stravaAcitivityIds[] = $stravaActivity->strava_id;
		}
		$wheatherUpdateModel = new RoadbikelifeModelApiupdatewheather();
		$wheatherUpdateModel->setStravaActivitiesIds($stravaAcitivityIds);
		$wheatherUpdateModel->update();

	}

	private function getStravaActivities()
	{
		$app       = JFactory::getApplication();
		$input     = $app->input;
		$contentId = $input->getInt('content_id', false);

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('fv.value as strava_id,fv.item_id as item_id');
		$query->from($db->quoteName('#__fields_values', 'fv'));
		$query->join('INNER', '#__content AS c  ON c.id = fv.item_id AND fv.field_id = 5 AND c.created_by = ' . $this->accessToken->user_id);
		$query->order($db->quoteName('c.created') . ' asc');

		if ($contentId !== false && (int) $contentId > 0)
		{
			$query->where($db->quoteName('c.id') . ' = ' . $db->quote($contentId));
		}
		else
		{
			$query->where(
				$db->quoteName('c.created') . ' >= ' .
				$db->quote(date('Y-m-d H:i:s',
						strtotime('now - ' . RoadbikelifeHelper::getParam('strava_update_date_limit') . ' DAYS')
					)
				)
			);

		}

		$db->setQuery($query);
		$this->stravaActivities = $db->loadObjectList();
		if($this->stravaActivities[0]->strava_id != '') {
			return $this->stravaActivities;
		}
	}



	private function updateStravaActivites()
	{
		// get Strava Acitivities

		$api = $this->stravaApi;

		foreach ($this->stravaActivities as $stravaActivityIdItem)
		{
			$stravaActivityId = trim($stravaActivityIdItem->strava_id);
			$stravaActivity   = $api->get("activities/$stravaActivityId");

			if ((int) $stravaActivityId > 0 && !isset($stravaActivity->errors))
			{
				$stravaActivityStreams = $api->get("activities/$stravaActivityId/streams", ['keys' => implode(',', $this->streamFields), 'resolution' => 'medium']);
				$photos                = $api->get("activities/$stravaActivityId/photos", ['size' => '250']);
				$photosXl              = $api->get("activities/$stravaActivityId/photos", ['size' => '1000']);
				$photos                = $this->combineStravaPhotos($photos, $photosXl);
				$originalSize          = $stravaActivityStreams[0]->original_size;
				$segmentEfforts        = $this->getSegmentsData($stravaActivity->segment_efforts);
				$polyline              = $stravaActivity->map->polyline;

				if (in_array($stravaActivity->type, $this->allowedActivityTypes) && $stravaActivity->distance > 5)
				{
					foreach ($stravaActivityStreams as $stravaActivityStream)
					{
						$stravaActivityStreamRestructured[$stravaActivityStream->type] = $stravaActivityStream->data;
					}
					$stravaActivityStreams = $stravaActivityStreamRestructured;
					$this->saveGmapsImage($stravaActivity);


					//remove unused
					unset($stravaActivity->map);
					unset($stravaActivity->photos);
					unset($stravaActivity->segment_efforts);
					unset($stravaActivity->splits_metric);
					unset($stravaActivity->splits_standard);

					$tableName                    = '#__strava_activity_stats';
					$fields                       = new stdClass();
					$fields->id                   = trim($stravaActivity->id);
					$fields->activity_json        = json_encode($stravaActivity);
					$fields->segment_efforts_json = json_encode($segmentEfforts);
					$fields->polyline             = $polyline;
					$fields->photos_json          = json_encode($photos);
					foreach ($stravaActivityStreams as $type => $stravaActivityStream)
					{
						$fields->{$type . '_json'} = json_encode($stravaActivityStream);
					}
					$fields->marker_json   = json_encode($this->getMarkerData($stravaActivityStreams, $stravaActivity));
					$fields->original_size = $originalSize;
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

	public function saveGmapsImage($stravaActivity)
	{
		$polyline = $stravaActivity->map->summary_polyline;
		$filename = $stravaActivity->id;
		$imageDir = 'images/gmaps_route_images/';
		if (!is_dir($imageDir))
		{
			mkdir($imageDir, $this->mkDirMode, true);
		}



        self::downloadImage($this->getGmapsImageUrl('200x200', $polyline),$imageDir . '/gmaps_' . $filename . '.png' );
        self::downloadImage($this->getGmapsImageUrl('1000x750', $polyline),$imageDir . '/gmaps_' . $filename . '_large.png' );



	}

    private static function downloadImage($url,$filename){
        if(file_exists($filename)){
            @unlink($filename);
        }
        $fp = fopen($filename,'w');
        if($fp){
            $ch = curl_init ($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            $result = parse_url($url);
            curl_setopt($ch, CURLOPT_REFERER, $result['scheme'].'://'.$result['host']);
            curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0');
            $raw=curl_exec($ch);
            curl_close ($ch);
            if($raw){
                fwrite($fp, $raw);
            }
            fclose($fp);
            if(!$raw){
                @unlink($filename);
                return false;
            }
            return true;
        }
        return false;
    }

	protected function getGmapsImageUrl($size, $polyline)
	{
		$imageUrl = RoadbikelifeHelper::getParam('google_maps_staticapi_url') . '?key=' . RoadbikelifeHelper::getParam('google_maps_apiKey') . '&size=' . $size . '&path=color:0xff0000ff|weight:3|enc:' . $polyline;

		return $imageUrl;
	}

	private function createImageFromFile($filename)
	{

        $imageRes = imagecreatefromstring(file_get_contents($filename));
		if (!isset($imageRes) ||!$imageRes) throw new InvalidArgumentException("\"" . $filename . "\" is not readable or no php supported format");
		else
		{
			return $imageRes;
		}
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
