<?php
/**
 * @copyright      Copyright (c) 2019 Roadbikelife. All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
use Joomla\CMS\Factory;

defined('_JEXEC') or die;
JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');
JLoader::register('RoadbikelifeModelApiupdatestrava', 'components/com_roadbikelife/models/apiupdatestrava.php');
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fields/models', 'FieldsModel');

#[AllowDynamicProperties] class modArticleStravaMap
{

    /**
     * Constructor.
     *
     * @param          $subject
     * @param array $config
     */

    public $stravaActivity;
    public $stravaActivities;
    public $googleMapHtml;
    public $roadbikelifeHelper;
    public $flotGraphs = [
        'altitude' => [
            'icon' => 'fa-mountain',
            'unit' => 'm'
        ],
        'distance' => [
            'icon' => 'fa fa-tachometer',
            'unit' => 'km/h'
        ],
        'watts' => [
            'icon' => 'fa-bolt',
            'unit' => 'w'
        ],
        'heartrate' => [
            'icon' => 'fa-heartbeat',
            'unit' => 'BPM'
        ],

    ];

    public $mapMarkers = [
        'grade_smooth' => [
            'tooltip_title' => 'Steilster Anstieg',
        ],
        'cadence' => [
            'tooltip_title' => 'Höchste Trittfrequenz',
        ],
        'watts' => [
            'tooltip_title' => 'Höchste Leistung',
        ],
        'heartrate' => [
            'tooltip_title' => 'Höchste HF',
        ],
        'distance' => [
            'tooltip_title' => 'Höchste Geschwindikeit',
        ],
        'altitude' => [
            'tooltip_title' => 'Höchste Erhebung',
        ],
    ];
    /**
     * @var \Joomla\Registry\Registry
     * @since version
     */
    private \Joomla\Registry\Registry $params;


    public function __construct(\Joomla\Registry\Registry $params)
    {
        $this->params = $params;
    }

    public function addScripts()
    {
        $doc = JFactory::getDocument();
        $flotJs = 'var map_data = ' . $this->getStravaActivityJson();
        $doc->addScriptDeclaration("let gmaps_js_url = 'https://maps.googleapis.com/maps/api/js?libraries=geometry&key=" .
            RoadbikelifeHelper::getParam('google_maps_apiKey') . "'");
        $doc->addScriptDeclaration("Joomla.gmap_styles_rbl_article = ".$this->params->get('gmap_styles','[]'));
        $doc->addScriptDeclaration($flotJs);
        $doc->addScript('/modules/mod_article_stravamap/assets/js/jquery.flot.js');
        $doc->addScript('/modules/mod_article_stravamap/assets/js/jquery.flot.resize.js');
        $doc->addScript('/modules/mod_article_stravamap/assets/js/load_map.js');
    }

    private function isMobile()
    {
        $app = Factory::getApplication();

        return $app->client->mobile;
    }

    private function getRoadbikelifeHelper()
    {
        if (!isset($this->roadbikelifeHelper)) {
            $this->roadbikelifeHelper = new RoadbikelifeHelper();
        }

        return $this->roadbikelifeHelper;
    }


    public function getStravaActivitiesTotals()
    {
        if (isset($this->stravaActivities)) {
            $distanceTotal = 0;
            $elevationTotal = 0;
            $caloriesTotal = 0;
            $wattsTotal = 0;
            $timeTotal = 0;

            foreach ($this->stravaActivities as $stravaActivitiy) {
                $distanceTotal += $stravaActivitiy->distance;
                $elevationTotal += $stravaActivitiy->total_elevation_gain;
                $timeTotal += $stravaActivitiy->moving_time;
                $caloriesTotal += $stravaActivitiy->calories;
                $wattsTotal += $stravaActivitiy->weighted_average_watts;
            }

            $averageSpeedTotal = $distanceTotal / $timeTotal;
            $averageWattsTotal = $wattsTotal / count($this->stravaActivities);

            $totals = [
                'distance' => RoadbikelifeHelper::formatRenderedValue($distanceTotal, 'distance', true, true),
                'moving_time' => RoadbikelifeHelper::formatRenderedValue($timeTotal, 'moving_time', true, true),
                'total_elevation_gain' => RoadbikelifeHelper::formatRenderedValue($elevationTotal, 'total_elevation_gain', true, true),
                'average_speed' => RoadbikelifeHelper::formatRenderedValue($averageSpeedTotal, 'average_speed', true, true),
                'average_watts' => RoadbikelifeHelper::formatRenderedValue($averageWattsTotal, 'average_watts', true, true),
                'calories' => RoadbikelifeHelper::formatRenderedValue($caloriesTotal, 'calories', true, true),
            ];

            return $totals;
        }


    }


    private function smoothData($array, $bucket_size)  // bucket filter
    {
        if (!is_array($array)) return false; // no empty arrays
        $buckets = array_chunk($array, $bucket_size);  // chop up array into bucket size units
        foreach ($buckets as $key => $bucket) {
            $new_array[$key] = array_sum($bucket) / count($bucket);
        };

        return $new_array;  // return new smooth array
    }


    private function getFlotgraphDataStream($stravaActivity, $streamName)
    {
        $streamName = $streamName . '_json';
        $smoothnessFactor = 7;
        $streamDatas = $stravaActivity->{$streamName};
        $jsDataArray = [];
        if ($streamName != 'altitude_json') {
            if ($this->isMobile()) {
                $smoothnessFactor = 10;
            }
            $streamDatas = $this->smoothData($streamDatas, $smoothnessFactor);
        }

        if ($streamDatas !== false) {
            foreach ($streamDatas as $key => $streamData) {
                if ($streamData === null) {
                    $streamData = 0;
                }
                if (isset($streamDatas[$key + 1])) {
                    if ($streamName == 'distance_json') {
                        $timeData = $this->smoothData($stravaActivity->time_json, $smoothnessFactor);
                        if (isset($streamDatas[$key + 1])) {
                            $time = $timeData[$key + 1] - $timeData[$key];
                            $difference = ($streamDatas[$key + 1] - $streamData) / $time;
                            $jsDataArray[] = [$key, $difference];
                        }
                    } else {
                        $jsDataArray[] = [$key, $streamData];

                    }
                }
            }

        }

        return $jsDataArray;
    }

    private function formatSegmentEfforts($stravaActivity)
    {
        foreach ($stravaActivity->segment_efforts_json as $index => &$segmentEffort) {
            if ($segmentEffort->achievements[0]->type === 'overall' && $segmentEffort->achievements[0]->rank <= 10) {
                ob_start();
                require JModuleHelper::getLayoutPath('mod_article_stravamap', 'default_segment_tooltip');
                $buffer = ob_get_contents();
                ob_end_clean();
                $segmentEffort->tooltip_content = $buffer;
            } else {
                unset($stravaActivity->segment_efforts_json[$index]);
            }
        }

        $stravaActivity->segment_efforts_json = array_values($stravaActivity->segment_efforts_json);

        return $stravaActivity;
    }

    public function getStravaActivityJson(): string
    {
        $stravaActivity = $this->getStravaActivity();
        $markerData = $stravaActivity->marker_json;
        $coordinates = $this->getCoordinates();


        $flotJs['coordinates'] = $coordinates;
        $flotJs['time'] = $stravaActivity->time_json;
        $flotJs['temp'] = $stravaActivity->temp_json;
        $flotJs['wheather'] = $stravaActivity->wheather_json;
        $flotJs['segment_efforts'] = $this->formatSegmentEfforts($stravaActivity)->segment_efforts_json;
        $flotJs['map_polyline'] = $stravaActivity->map_polyline;

        foreach ($this->mapMarkers as $markerName => $marker) {
            if (isset($markerData->{$markerName})) {
                $json = [
                    $flotJs['markers'][$markerName]['text'] = '<span class="stravaMapValueWrapper">' .
                        $marker['tooltip_title'] . ': ' . $markerData->$markerName->max_value . '</span>',
                    $flotJs['markers'][$markerName]['coordinates'] = [
                        'lat' => $markerData->$markerName->max_latlng[0],
                        'lng' => $markerData->$markerName->max_latlng[1]
                    ],
                ];
            }
        }

        foreach ($this->flotGraphs as $flotGraphName => $flotGraph) {
            $data = $this->getFlotgraphDataStream($stravaActivity, $flotGraphName);
            if (isset($data)) {
                $flotJs['graphs'][$flotGraphName] = $data;
            }
        }

        $photos = $stravaActivity->photos_json;

        $flotJs['photos'] = [];
        foreach ($photos as $key => $photo) {
            if (is_int($key)) {
                $flotJs['photos'][$key] = $photo;
            }
        }

        return json_encode($flotJs);

    }


    public function getStravaActivitiesforModule()
    {
        $app = JFactory::getApplication();
        $id = $app->input->getInt('id', 0);
        $fieldModel = JModelLegacy::getInstance('Field', 'FieldsModel');
        $stravaActivityFieldValues = $fieldModel->getFieldValue(5, $id);

        if (is_array($stravaActivityFieldValues) && count($stravaActivityFieldValues) > 1) {
            if (!isset($this->stravaActivities)) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select([
                    'sas.activity_json',
                ]);
                $query->from($db->quoteName('#__strava_activity_stats', 'sas'));
                $query->where([$db->quoteName('sas.id') . ' IN (' . implode(',', $stravaActivityFieldValues) . ')']);
                $query->order('sas.id ASC');
                $db->setQuery($query);
                $stravaActivities = $db->loadObjectList();
                foreach ($stravaActivities as $key => $stravaActivity) {
                    $stravaActivities[$key] = json_decode($stravaActivity->activity_json);
                }
                $this->stravaActivities = $stravaActivities;
            }

            return $this->stravaActivities;
        }
    }

    public function getStravaActivity()
    {

        if (isset($this->stravaActivity)) {
            return $this->stravaActivity;
        }

        $app = JFactory::getApplication();
        $id = $app->input->getInt('id', 0);
        $stravaActivityId = $app->input->getInt('strava_activity_id', 0);

        // if no id provided by url (ajax)
        if ($stravaActivityId == 0) {
            $fieldModel = JModelLegacy::getInstance('Field', 'FieldsModel');
            $stravaActivities = $fieldModel->getFieldValue(5, $id);

            if (is_array($stravaActivities)) {
                $stravaActivities = array_reverse($stravaActivities);
                $stravaActivityId = $stravaActivities[0];
            } else {
                $stravaActivityId = $stravaActivities;
            }
        }

        if ((int)$stravaActivityId > 0) {


            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select([
                'sas.*',
                'sw.wheather_json'
            ]);


            if($id >= 48) {
                $wheatherTableName = '#__strava_activity_open_wheather';
            } else {
                $wheatherTableName = '#__strava_activity_wheather';
            }

            $query->join('LEFT', "$wheatherTableName AS sw  ON sw.id = sas.id");
            $query->from($db->quoteName('#__strava_activity_stats', 'sas'));
            $query->where($db->quoteName('sas.id') . ' = ' . $stravaActivityId);
            $db->setQuery($query);
            $stravaActivity = $db->loadObject();
            $stravaActivityPrepared = new stdClass();

            foreach ($stravaActivity as $fieldName => $stravaActivityField) {
                if (strpos($fieldName, 'json') > 0) {
                    $stravaActivityPrepared->$fieldName = json_decode($stravaActivityField);
                }
            };
            $stravaActivityPrepared->map_polyline = $stravaActivity->polyline;
            $this->stravaActivity = $stravaActivityPrepared;

            return $this->stravaActivity;
        }
    }

    public function getCoordinates()
    {
        $stravaActivity = $this->getStravaActivity();
        $coords = $stravaActivity->latlng_json;
        foreach ($coords as $key => $coord) {
            $CoordsOfRide[$key]['lat'] = $coord[0];
            $CoordsOfRide[$key]['lng'] = $coord[1];
        }

        return $CoordsOfRide;
    }

}
