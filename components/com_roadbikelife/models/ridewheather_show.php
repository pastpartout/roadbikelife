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
class RoadbikelifeModelRidewheathershow extends RoadbikelifeModelRoadbikelife
{


    public $item;
    public $token;
    public $routeCoords;
    public $googleMapHtml;
    public $roadbikelifeHelper;
    private $tableName = '#__ridewheather';

    public $flotGraphs = [
        'apparentTemperature' => [
            'icon' => 'fa fa-temperature-hot',
            'unit' => '°C'
        ],
        'temperature' => [
            'icon' => 'fa fa-thermometer-half',
            'unit' => '°C'
        ],
        'precipProbability' => [
            'icon' => 'fa fa-humidity',
            'unit' => '%'
        ],
        'precipIntensity' => [
            'icon' => 'fa fa-raindrops',
            'unit' => '%'
        ],
        'windSpeed' => [
            'icon' => 'fa-wind',
            'unit' => 'km/h'
        ],
        'windGust' => [
            'icon' => 'fa fa-windsock',
            'unit' => 'km/h'
        ],

        'uvIndex' => [
            'icon' => 'fa fa-sunglasses',
            'unit' => ''
        ],


    ];

    public $averageItems = [
        'apparentTemperature' => [
            'icon' => 'fa fa-temperature-hot',
            'unit' => '°C'
        ],
        'temperature' => [
            'icon' => 'fa fa-thermometer-half',
            'unit' => '°C'
        ],
        'precipProbability' => [
            'icon' => 'fa fa-humidity',
            'unit' => '%'
        ],

        'windSpeed' => [
            'icon' => 'fa-wind',
            'unit' => 'km/h'
        ],
        'windBearing' => [
            'icon' => 'fa-arrow-up',
            'unit' => '°'
        ],
        'windGust' => [
            'icon' => 'fa-windsock',
            'unit' => 'km/h'
        ],

    ];

    public $isSmartphone;

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
    ];

    public $clothingItems = [
        'shoe_warmers' => [],
        'socks_thin' => [],
        'socks_thick' => [],
        'jersey_short' => [],
        'jersey_long' => [],
        'bib_short' => [],
        'bib_long' => [],
        'cap' => [],
        'snood' => [],
        'jacket_thin' => [],
        'jacket_thick' => [],
        'jacket_rain' => [],
        'gloves_thin' => [],
        'gloves_thick' => [],
        'arm_warmers' => [],
        'leg_warmers' => [],
    ];

    public function addScripts()
    {
	    $doc = JFactory::getDocument();
	    JHtml::_('jquery.framework');

	    $flotJs = $this->getStravaActivityJson();
	    $doc = JFactory::getDocument();
	    $doc->addScriptDeclaration($flotJs);

        $doc->addScript("https://maps.googleapis.com/maps/api/js?key=" . RoadbikelifeHelper::getParam('google_maps_apiKey'));
        $doc->addScript('/media/com_roadbikelife/js/gmaps-markerwithlabel-1.9.1.js');
        $doc->addScript('/node_modules/flot/dist/es5/jquery.flot.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.resize.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.time.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.hover.js');
        $doc->addScript('/node_modules/jquery.flot.tooltip/js/jquery.flot.tooltip.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.touch.js');
        $doc->addScript('/modules/mod_article_stravamap/assets/js/curvedLines.js');
        $doc->addScript('/media/com_roadbikelife/js/init_map.js?v=3');
        $doc->addScript('/media/com_roadbikelife/js/init_flot.js?v=3');
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }


    private function isMobile()
    {
        $detect = new Mobile_Detect;

        if (!isset($this->isSmartphone)) {
            if ($detect->isMobile() && !$detect->isTablet()) {
                $this->isSmartphone = true;
            } else {
                $this->isSmartphone = false;
            }
        }

        return $this->isSmartphone;
    }

    private function getRoadbikelifeHelper()
    {
        if (!isset($this->roadbikelifeHelper)) {
            $this->roadbikelifeHelper = new RoadbikelifeHelper();
        }
        return $this->roadbikelifeHelper;
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


    private function getCreateModel()
    {
        if (!isset($this->createModel)) {
            JLoader::register('RoadbikelifeModelRidewheather', __DIR__ . '/ridewheather.php');
            $this->createModel = new RoadbikelifeModelRidewheather();
        }

        return $this->createModel;
    }

    private function getDistanceModulo()
    {
        $distanceTotal = $this->item->distance;
        $wheatherCount = json_decode($this->item->wheather_json);
        $wheatherCount = count($wheatherCount) - 1;
        return intval($distanceTotal / $wheatherCount);
    }

    private function getFlotgraphDataStream($graphName)
    {

        $this->getCreateModel();
        $wheatherData = json_decode($this->item->wheather_json);
        $distanceModulo = $this->getDistanceModulo();

        foreach ($wheatherData as $key => $data) {
            $distance = ($key + 1) * $distanceModulo * 1000;

            if ($graphName == 'precipProbability' || $graphName == 'precipIntensity') {
                $data->data->$graphName = $data->data->$graphName * 100;
            }

            $graphData[] = [$distance, $data->data->$graphName];
        }

        return $graphData;
    }

    public function getWindBearingName($value)
    {
        $windBearingMap = $this->getCreateModel()->windBearingMap;
        $windBearingMap = array_reverse($windBearingMap);
        foreach ($windBearingMap as $windBaaringNameValue => $item) {
            if ($value <= floatval($windBaaringNameValue)) {
                $name = $item;
            }
        }
        return $name;
    }

    private function getStravaActivityJson()
    {

        $coordinates = $this->getCoordinates();
        $flotJs['coordinatesStart'] = $coordinates[0];
        $flotJs['coordinatesEnd'] = $coordinates[count($coordinates) - 1];
        $flotJs['start_date_local'] = strtotime($this->item->start_date);

        $flotJs['coordinatesJson'] = $coordinates;
        $flotJs['wheather'] = json_decode($this->item->wheather_json);

        foreach ($this->flotGraphs as $flotGraphName => $flotGraph) {
            $data = $this->getFlotgraphDataStream($flotGraphName);
            if (isset($data)) {
                $flotJs['flot'][$flotGraphName] = $data;
            }
        }

        $flotJs['graph_types'] = $this->flotGraphs;

        return 'var map_data = ' . json_encode($flotJs);

    }


    public function getItem()
    {

        if (!isset($this->item)) {
            $jinput = JFactory::getApplication()->input;

            if (!isset($this->token)) {
                $this->token = $jinput->getString('token', '');
            }

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName($this->tableName));
            $query->where($db->quoteName('token') . ' = ' . $db->quote($this->token));
            $query->setLimit(1);
            $db->setQuery($query);
            $this->item = $db->loadObject();
            $this->item->wheather = json_decode($this->item->wheather_json);
            $this->item->moving_time = $this->getMovingTime();
            $this->item->finish_time = $this->getFinishTime();
            $this->item->clothing_recs = $this->getClothingRecs();

            if ($this->item->title == '') {
                $this->item->title = $this->item->gpx_file;
            }
        }

        return $this->item;

    }

    public function formatWheather()
    {
        $thisItem = $this->item;
        foreach ($this->item->wheather as $key1 => $wheatherItem) {
            foreach ($wheatherItem->data as $key2 => $item) {
                if (is_float($item)) {
                    $value = number_format($item, 1, ',', '.');
                    $thisItem->wheather[$key1]->data->$key2 = $value;
                }
            };
        };
        return $thisItem;

    }


    private function getClothingRecs()
    {
        foreach ($this->item->wheather as $weatherItem) {
            $temperatures[] = $weatherItem->data->apparentTemperature;
        }
        sort($temperatures);
        $temperature = $temperatures[0];
        $temperatureMax = $temperatures[count($temperatures) - 1];

        $clothes = $this->clothingItems;

        if($this->item->icon_avg == 'fa-sun') {
            $temperature = $temperature + 2;
        }

        if($this->item->windspeed_avg > '15') {
            $temperature = $temperature + 3;
        }

        if ($temperature <= -5) {
            $clothesPoints['socks_thick'] = +5;
            $clothesPoints['socks_thin'] = +5;
            $clothesPoints['jersey_long'] = +5;
            $clothesPoints['jersey_short'] = +5;
            $clothesPoints['bib_long'] = +5;
            $clothesPoints['snood'] = +5;
            $clothesPoints['jacket_thick'] = +5;
            $clothesPoints['gloves_thick'] = +5;
            $clothesPoints['gloves_thin'] = +5;
            $clothesPoints['shoe_warmers'] = +5;
        }

        if ($temperature <= 0) {
            $clothesPoints['socks_thick'] = +5;
            $clothesPoints['socks_thin'] = +2;
            $clothesPoints['jersey_long'] = +5;
            $clothesPoints['jersey_short'] = +5;
            $clothesPoints['bib_long'] = +5;
            $clothesPoints['snood'] = +5;
            $clothesPoints['jacket_thick'] = +5;
            $clothesPoints['gloves_thick'] = +5;
            $clothesPoints['gloves_thin'] = +2;
            $clothesPoints['shoe_warmers'] = +5;
        }

        if ($temperature > 0 && $temperature < 5) {
            $clothesPoints['socks_thick'] = +5;
            $clothesPoints['jersey_long'] = +5;
            $clothesPoints['bib_long'] = +5;
            $clothesPoints['snood'] = +4;
            $clothesPoints['jacket_thin'] = +5;
            $clothesPoints['gloves_thin'] = +5;
            $clothesPoints['shoe_warmers'] = +3;
        }

        if ($temperature >= 5 && $temperature < 10) {
            $clothesPoints['socks_thick'] = +5;
            $clothesPoints['jersey_long'] = +5;
            $clothesPoints['bib_long'] = +5;
            $clothesPoints['snood'] = +3;
            $clothesPoints['jacket_thin'] = +5;
            $clothesPoints['gloves_thin'] = +4;



            if($this->item->apparenttemperature_avg > 12.5) {
                unset( $clothesPoints['bib_long']);
                unset( $clothesPoints['socks_thick']);
                $clothesPoints['bib_short'] = +5;
                $clothesPoints['leg_warmers'] = +5;
                $clothesPoints['socks_thin'] = +5;
            }

        }

        if ($temperature >= 10 && $temperature < 15) {
            $clothesPoints['socks_thin'] = +5;
            $clothesPoints['jersey_long'] = +5;
            $clothesPoints['bib_short'] = +5;
            $clothesPoints['jacket_thin'] = +3;

            if($temperatureMax > 15) {
                unset( $clothesPoints['jersey_long']);
                $clothesPoints['jersey_short'] = +5;
                $clothesPoints['arm_warmers'] = +5;
                $clothesPoints['jacket_thin'] = 1;
            }
        }

        if ($temperature >= 15 && $temperature < 20) {
            $clothesPoints['socks_thin'] = +5;
            $clothesPoints['jersey_long'] = +5;
            $clothesPoints['bib_short'] = +5;
        }

        if ($temperature >= 20 && $temperature < 25) {
            $clothesPoints['socks_thin'] = +5;
            $clothesPoints['jersey_short'] = +5;
            $clothesPoints['bib_short'] = +5;
        }

        if ($temperature >= 25) {
            $clothesPoints['socks_thin'] = +5;
            $clothesPoints['jersey_short'] = +5;
            $clothesPoints['bib_short'] = +5;
        }

        if ($this->item->precipprobability_avg > 0) {

            if ($temperature >= 20){
                unset( $clothesPoints['jacket_thin']);
                $clothesPoints['jacket_rain'] = $this->item->precipprobability_avg * 50 / 2 / 10;
            } else {
                $clothesPoints['jacket_rain'] = $this->item->precipprobability_avg  * 100 / 2 / 10 + 1;
            }

            if ($this->item->precipprobability_avg > 40) {
                if ($temperature < 0) {
                    $clothesPoints['socks_thin'] = +3;
                    $clothesPoints['jersey_short'] = +2;
                    $clothesPoints['snood'] = +2;
                    $clothesPoints['shoe_warmers'] = 5;
                }
            }
        }

        $clothesPointsSorted = $clothesPoints;
        arsort($clothesPointsSorted);
        $highestClothingScore = 5;

        foreach ($clothesPointsSorted as $clothingItemName => $clothingItemPoint) {
            $points = $clothesPoints[$clothingItemName];
            $percents = round(100 / ($highestClothingScore / $clothingItemPoint));
            $clothingItemScores[$clothingItemName] = $percents;
        }

        return $clothingItemScores;

    }

    public function getItems()
    {

//        if (!isset($this->item)) {
        $jinput = JFactory::getApplication()->input;
        $user = JFactory::getUser();

        if (!isset($this->token)) {
            $this->token = $jinput->getString('token', '');
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($this->tableName));
        $query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
        $query->setLimit(1);
        $query->order('created DESC');
        $db->setQuery($query);
        $items = $db->loadObjectList();

        foreach ($items as $item) {
            $this->item = $item;

            $item->wheather = json_decode($item->wheather_json);
            $item->moving_time = $this->getMovingTime();
            $item->finish_time = $this->getFinishTime();

            if ($item->title == '') {
                $item->title = $item->gpx_file;
            }
        }

//        }

        return $items;

    }

    private function getFinishTime()
    {
        $time = ($this->item->distance * 1000) / ($this->item->speed / 3.6);
        $time = $this->item->start_date + $time;
        return $time;

    }

    private function getMovingTime()
    {
        $time = ($this->item->distance * 1000) / ($this->item->speed / 3.6);
        return gmdate("H:i:s", $time);

    }


    private function getCoords()
    {
        $fileUploadPathGpx = 'media/com_roadbikelife/ridewheather/gpxuploads/' . $this->item->token . '/' . $this->item->gpx_file;
        $gpxFile = file_get_contents($fileUploadPathGpx);
        $this->gpxFile = new SimpleXMLElement($gpxFile);
        $i = 0;
        foreach ($this->gpxFile->trk->trkseg->trkpt as $key => $trkpt) {
            $trkptlat = (float)$trkpt->attributes()->lat;
            $trkptlon = (float)$trkpt->attributes()->lon;
            $coords[] = [
                'lat' => $trkptlat,
                'lng' => $trkptlon
            ];
        }

        $this->itemCoords = $coords;
        return $coords;
    }

    public function getCoordinates()
    {
        $route = $this->getItem();
        $coords = $this->getCoords();
        return $coords;
    }

}
