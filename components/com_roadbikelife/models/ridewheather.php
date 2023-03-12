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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');
JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . '/components/com_roadbikelife/helpers/roadbikelife.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelRidewheather extends RoadbikelifeModelRoadbikelife
{


    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */
    private $apiKey;
    private $fileUploadPath;
    private $gpxModel;
    private $showModel;
    private $gpxFile;
    private $gpxFileName;
    private $rideStartDate;
    private $rideSpeed;
    private $token;
    private $polyline;
    private $iconsMap = [
        'clear-day' => 'fa-sun',
        'clear-night' => 'fa-moon',
        'rain' => 'fa-cloud-rain',
        'snow' => 'fa-cloud-snow',
        'sleet' => 'fa-cloud-sleet',
        'wind' => 'fa-wind',
        'fog' => 'fa-fog',
        'cloudy' => 'fa-clouds',
        'partly-cloudy-day' => 'fa-clouds-sun',
        'partly-cloudy-night' => 'fa-clouds-moon',
        'hail' => 'fa-clouds-hail',
        'thunderstorm' => 'fa-thunderstorm',
        'tornado' => 'fa-tornado',
    ];

    public $windBearingMap = [
        '22.5' => 'NNO',
        '45' => 'NO',
        '67.5' => 'ONO',
        '90' => 'O',
        '112.5' => 'OSO',
        '135' => 'SO',
        '157.5' => 'SO',
        '180' => 'S',
        '202.5' => 'SSW',
        '225' => 'SW',
        '247.5' => 'WSW',
        '270' => 'W',
        '292.5' => 'WNW',
        '315' => 'NW',
        '337.5' => 'NNW',
        '360' => 'N',

    ];

    public $uploadLimits = [
        'day' => 25,
        'total' => 100
    ];


    public $wheatherResolutuion = 12;

    private $tableName = '#__ridewheather';

    public function __construct($config = array())
    {
        $this->setApiKey();
        parent::__construct($config);
    }

    /**
     * @param mixed $rideStartDate
     */
    public function setRideStartDate($jinput): void
    {
        $rideDateStart = $jinput->getString('date_start', 'now');
        if ($rideDateStart == '') $rideDateStart = 'now';
        $this->rideStartDate = strtotime($rideDateStart);
    }


    public function setToken(): void
    {
        $this->token = $this->generateToken();
    }

    /**
     * @param mixed $jinput
     */
    public function setApiKey(): void
    {
        $darkskyweather_apiKey = RoadbikelifeHelper::getParam('darkskyweather_apiKey');
        $this->darkskyweather_apiKey = $darkskyweather_apiKey;
    }

    /**
     * @param mixed $jinput
     */
    public function setRideSpeed($jinput): void
    {
        $rideSpeed = $jinput->get('speed', '26', 'string');
        $this->rideSpeed = $rideSpeed;
    }


    public function getApiKey()
    {
        return RoadbikelifeHelper::getParam('darkskyweather_apiKey');
    }

    private function checkLimits()
    {
        $app = Factory::getApplication();

        if (strpos(JURI::current(), 'localhost') != false) {
            return false;
        } elseif (RoadbikelifeHelper::getParam('upload_allowed') == '0') {
	        $app->enqueueMessage(Text::_('Sorry, momentan sind keine neuen Wettervorhersagen möglich.'), 'warning');
            $app->redirect(Route::_('index.php?option=com_roadbikelife&view=ridewheather&task=list'));
            exit;
        }


        $limitReached = false;
        $user = JFactory::getUser();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($db->quoteName($this->tableName));
        $query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
        $query->setLimit(1);
        $query->order('created DESC');
        $db->setQuery($query);
        $itemsCountTotal = $db->loadResult();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($db->quoteName($this->tableName));
        $query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
        $query->where('UNIX_TIMESTAMP(' . $db->quoteName('created') . ') > ' . $db->quote(strtotime('now - 24 hours')));
        $query->setLimit(1);
        $query->order('created DESC');
        $db->setQuery($query);
        $itemsCountToday = $db->loadResult();


        if ($itemsCountTotal > $this->uploadLimits['total']) {
            $limitReached = true;
            $app->redirect(JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=create', false), 'Maximale Anzahl von ' . $this->uploadLimits['total'] . ' Uploads erreicht', 'danger');

            exit;
        }

        if ($itemsCountToday > $this->uploadLimits['day']) {
            $limitReached = true;
            $app->redirect(JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=create', false), 'Maximale Anzahl von ' . $this->uploadLimits['day'] . ' täglichen Uploads erreicht', 'danger');
            exit;
        }

        return $limitReached;
    }

    public function getLimitStatus()
    {
        $user = JFactory::getUser();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($db->quoteName($this->tableName));
        $query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
        $query->setLimit(1);
        $query->order('created DESC');
        $db->setQuery($query);
        $itemsCountTotal = $db->loadResult();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($db->quoteName($this->tableName));
        $query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
        $query->where('UNIX_TIMESTAMP(' . $db->quoteName('created') . ') > ' . $db->quote(strtotime('now - 24 hours')));
        $query->setLimit(1);
        $query->order('created DESC');
        $db->setQuery($query);
        $itemsCountToday = $db->loadResult();

        $status = new stdClass();
        $status->day = new stdClass();
        $status->total = new stdClass();

        $status->day->percent = round($itemsCountToday / $this->uploadLimits['day'] * 100);
        $status->day->count = $itemsCountToday;

        $status->total->percent = round($itemsCountTotal / $this->uploadLimits['total'] * 100);
        $status->total->count = $itemsCountTotal;

        return $status;

    }

    public function getAccessTokenCode()
    {
        $jinput = JFactory::getApplication()->input;
        $code = $jinput->get('code', '', 'RAW');
        return $code;
    }

    public function getStravaLogin()
    {




	    JLoader::register('StravaApi', JPATH_ROOT . '/components/com_roadbikelife/models/includes/StravaApi.php');

        $stravaApi = new StravaApi (
            RoadbikelifeHelper::getParam('strava_clientId'),
            RoadbikelifeHelper::getParam('strava_clientSecret')
        );

        $app = JFactory::getApplication();

        $code = $this->getAccessTokenCode();

        if ($code != '') {

            $accessToken = $stravaApi->tokenExchange($code);

            $stravaApi->setAccessToken($accessToken->access_token);
            $stravaMember = $accessToken->athlete;

            // Exit when error on strava login
            if (!isset($stravaMember->id)) {
                $link = JURI::base() . 'index.php?option=com_roadbikelife&view=ridewheather&task=create';
                $app->redirect($link, 'Strava-Anmeldung nicht erfolgreich!', 'warning');
                exit;
            }

            // Strava has logged in
            $user = JFactory::getUser();
            $tableName = '#__strava_member_tokens';
            $fields = new stdClass();
            $fields->id = $stravaMember->id;
            $fields->user_id = $user->id;
            $fields->token = $accessToken->access_token;
            $fields->refresh_token = $accessToken->refresh_token;
            $fields->created = date("Y-m-d H:i:s");
            $db = JFactory::getDbo();
            try {
                $query = $db->getQuery(true);
                $result = JFactory::getDbo()->insertObject($tableName, $fields);
            } catch (Exception $e) {
                $result = JFactory::getDbo()->updateObject($tableName, $fields, 'id');
            }
        }


    }

    private function athenticate()
    {
        JLoader::register('StravaApi', JPATH_ROOT . '/components/com_roadbikelife/models/includes/StravaApi.php');

        $stravaApi = new StravaApi (
            RoadbikelifeHelper::getParam('strava_clientId'),
            RoadbikelifeHelper::getParam('strava_clientSecret')
        );
        if (!isset($this->stravaApi)) $this->stravaApi = $stravaApi;


        $db = JFactory::getDbo();
        $tableName = '#__strava_member_tokens';
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($tableName));
        $query->where($db->quoteName('user_id') . ' = ' . $db->quote(JFactory::getUser()->id));
        $db->setQuery($query);
        $savedTokens = $db->loadObject();
        return $savedTokens;
    }

    private function throwErrors($response)
    {
        if (isset($response->errors)) {
            echo json_encode($response);
            exit;
        }
    }

    private function refreshToken($savedToken)
    {
        $db = JFactory::getDbo();
        $accessToken = $savedToken->token;
        $athleteId = $savedToken->id;
        $api = $this->stravaApi;
        $today = date('d.m.Y H:i:s');

        if (
            strtotime($today) > strtotime($savedToken->created . ' + 1 hour')
        ) {
            if ($savedToken->refresh_token == '') {
                $newToken = $api->refreshToken($savedToken->token);
            } else {
                $newToken = $api->refreshToken($savedToken->refresh_token);
            }

            $this->throwErrors($newToken);

            if ($newToken->access_token != '' && $newToken->refresh_token != '') {
                $query = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('id') . ' = ' . $db->quote($athleteId),
                );
                $fields = array(
                    $db->quoteName('token') . ' = ' . $db->quote($newToken->access_token),
                    $db->quoteName('refresh_token') . ' = ' . $db->quote($newToken->refresh_token),
                    $db->quoteName('created') . ' = NOW()'
                );
                $tableName = '#__strava_member_tokens';
                $query->update($db->quoteName($tableName))->set($fields)->where($conditions);
                $db->setQuery($query);
                $result = $db->execute();
                $savedToken->access_token = $newToken->access_token;
            }

        } else {
            $api->setAccessToken($accessToken);
        }

        return $savedToken;
    }

    public function getStravaRoutes()
    {

        $savedToken = $this->athenticate();

        if (isset($savedToken)) {
            $savedToken = $this->refreshToken($savedToken);

            if (isset($savedToken)) {
                $athleteId = $savedToken->id;
                $stravaRoutes = $this->stravaApi->get("/athletes/$athleteId/routes");
                $stravaRoutes;
            } else {
                exit ('no Token received. Strava User is not Valid?!');
            }

        }

        return $stravaRoutes;
    }

    public function getStravaRoute($id)
    {

        $savedToken = $this->athenticate();
        $api = $this->stravaApi;

        if (isset($savedToken)) {
            $savedToken = $this->refreshToken($savedToken);

	        $api = $this->stravaApi;
	        $stravaRoute = $api->get("/routes/$id");

        } else {
	        exit ('no Token received. Strava User is not Valid?!');

        }
        $this->stravaRoute = $stravaRoute;
        return $stravaRoute;
    }

    public function getStravaLoginButtonUrl()
    {
        JLoader::register('StravaApi', JPATH_ROOT . '/components/com_roadbikelife/models/includes/StravaApi.php');

        $stravaApi = new StravaApi (
            RoadbikelifeHelper::getParam('strava_clientId'),
            RoadbikelifeHelper::getParam('strava_clientSecret')
        );

        return $stravaApi->authenticationUrl(JURI::base() . 'index.php?option=com_roadbikelife&view=ridewheather&task=create&strava_login=1', 'auto', 'read_all');
    }


    private function upload($src, $dest)
    {
        jimport('joomla.client.helper');
        $FTPOptions = JClientHelper::getCredentials('ftp');
        $ret = false;
        $dest = JPath::clean($dest);
        $baseDir = dirname($dest);
        if (!file_exists($baseDir)) {
            jimport('joomla.filesystem.folder');
            JFolder::create($baseDir);
        }

        if ($FTPOptions['enabled'] == 1) {
            jimport('joomla.client.ftp');
            $ftp = &JFTP::getInstance($FTPOptions['host'], $FTPOptions['port'], null, $FTPOptions['user'], $FTPOptions['pass']);
            $dest = JPath::clean(str_replace(JPATH_ROOT, $FTPOptions['root'], $dest), '/');
            if (is_uploaded_file($src) && $ftp->store($src, $dest)) {
                $ret = true;
                unlink($src);
            } else {
                JError::raiseWarning(21, JText::_('WARNFS_ERR02'));
            }
        } else {
            if (is_writeable($baseDir) && move_uploaded_file($src, $dest)) { // Short circuit to prevent file permission errors
                if (JPath::setPermissions($dest)) {
                    $ret = true;
                } else {
                    JError::raiseWarning(21, JText::_('WARNFS_ERR01'));
                }
            } else {
                JError::raiseWarning(21, JText::_('WARNFS_ERR02'));
            }
        }
        return $ret;
    }


    public function getForm()
    {
        $form = JForm::getInstance('com_content.article', 'components/com_roadbikelife/models/forms/ridewheather_upload.xml');

        if (empty($form)) {
            return false;
        }

        return $form;
    }


    private function getGpxModel()
    {
        if (!isset($this->gpxModel)) {
            JLoader::register('RoadbikelifeModelRidewheathergpx', __DIR__ . '/ridewheather_gpx.php');
            $this->gpxModel = new RoadbikelifeModelRidewheathergpx();
        }
        $this->gpxModel;
    }

    private function getShowModel()
    {
        if (!isset($this->showModel)) {
            JLoader::register('RoadbikelifeModelRidewheathershow', __DIR__ . '/ridewheather_show.php');
            $this->showModel = new RoadbikelifeModelRidewheathershow();
        }
        return $this->showModel;
    }

    private function getWheatherApiResponse($trkptlat, $trkptlon, $time)
    {
        $this->darkskyweather_apiKey = $this->getApiKey();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.darksky.net/forecast/$this->darkskyweather_apiKey/$trkptlat,$trkptlon,$time?lang=de&units=ca",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
	        CURLOPT_SSL_VERIFYPEER => 0
    ));

        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            exit();
        }
        $response->currently->icon = $this->iconsMap[$response->currently->icon];

        return $response->currently;
    }

    protected function generateToken($length = 32)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }


    private function save()
    {
        $wheather = $this->getWheatherData();
        if (count($wheather) < 1) {
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=create', false), 'Es konnten keine Wetterdaten abgerufen werden', 'danger');
        }

        //User
        $user = JFactory::getUser();
        // Save
        $db = JFactory::getDbo();
        $fields = new stdClass();
        $fields->wheather_json = json_encode($wheather);
        $fields->created = date("Y-m-d H:i:s");
        $fields->user_id = $user->id;
        $fields->title = $this->gpxModel->getTitle($this->gpxFile);
        $fields->gpx_file = $this->gpxFileName;
        $fields->polyline = $this->polyline;

        $fields->distance = $this->gpxModel->getDistance($this->gpxFile);
        $fields->start_date = $this->rideStartDate;
        $fields->speed = $this->rideSpeed;
        $fields->total_elevation_gain = $this->gpxModel->getElevation($this->gpxFile);
        if (!intval($fields->total_elevation_gain) > 0) {
            $fields->total_elevation_gain = intval($this->stravaRoute->elevation_gain);
        }

        $fields->token = $this->token;

        $averages = $this->getWheatherAverageData($wheather);

        foreach ($averages as $fieldName => $average) {
            $fields->{strtolower($fieldName) . '_avg'} = $average;
        }
        $fields->icon_avg = $this->getIconAvg($wheather);
        $fields->windbearingname_avg = $this->getWindBearingNameAvg($wheather);


        try {
            $query = $db->getQuery(true);
            $result = JFactory::getDbo()->insertObject($this->tableName, $fields);
        } catch (Exception $e) {
            $result = JFactory::getDbo()->updateObject($this->tableName, $fields, 'token');
        }
    }


    private function getWheatherData()
    {

        if (strpos(JURI::current(), 'localhost') != false) {
            $this->wheatherResolutuion = 2;
        }

        $modulo = intval(count($this->gpxFile->trk->trkseg->trkpt) / $this->wheatherResolutuion);
        $index = 0;
        foreach ($this->gpxFile->trk->trkseg->trkpt as $key => $trkpt) {
            if ($index % $modulo == 0) {
                $trkptlat = (float)$trkpt->attributes()->lat;
                $trkptlon = (float)$trkpt->attributes()->lon;
                $points[] = [
                    'lat' => $trkptlat,
                    'lng' => $trkptlon
                ];
            }
            $index++;
        }
        $pointsCount = count($points);

        foreach ($points as $key => $point) {
            if ($key > 0) {
                $distanceTotal = $this->gpxModel->getDistance($this->gpxFile);
                $distance = $key * $distanceTotal / $pointsCount * 1000;
                $timeToAdd = intval($distance / ($this->rideSpeed / 3.6));
                $time = intval($this->rideStartDate + $timeToAdd);
            } else {
                $distance = 0;
                $time = intval($this->rideStartDate);
            }

            $data = [
                'lat' => $point['lat'],
                'lng' => $point['lng'],
                'time' => $time,
                'data' => $this->getWheatherApiResponse($point['lat'], $point['lng'], $time)
            ];

            $data = $this->extendWindBearingNames($data);
            $wheather[] = $data;
        }


        return $wheather;

    }

    private function extendWindBearingNames($data)
    {
        $windBearingMap = array_reverse($this->windBearingMap);
        foreach ($windBearingMap as $windBaaringNameValue => $item) {
            if ($data['data']->windBearing <= floatval($windBaaringNameValue)) {
                $data['data']->windBearingName = $item;
            }
        }
        return $data;
    }

    private function getWindBearingNameAvg($wheather)
    {
        $windBearingNames = [];
        foreach ($wheather as $item) {
            $item = $item['data'];
            $windBearingNames[$item->windBearingName] = isset($windBearingNames[$item->windBearingName]) ? $windBearingNames[$item->windBearingName] + 1 : $windBearingNames[$item->windBearingName] = 1;
        };
        $windBearingNames = array_flip($windBearingNames);
        krsort($windBearingNames);
        $windBearingNames = array_values($windBearingNames);
        $windBearingName = $windBearingNames[0];
        return $windBearingName;
    }


    private function getIconAvg($wheather)
    {
        $icons = [];
        foreach ($wheather as $item) {
            $item = $item['data'];
            $icons[$item->icon] = isset($icons[$item->icon]) ? $icons[$item->icon] + 1 : $icons[$item->icon] = 1;
        };
        $icons = array_flip($icons);
        krsort($icons);
        $icons = array_values($icons);
        $icon = $icons[0];
        return $icon;
    }

    private function getWheatherAverageData($wheather)
    {
        $this->getShowModel();
        $averageItems = $this->showModel->averageItems;
        $averageItemCount = count($wheather);
        foreach ($averageItems as $averageItemName => $avergaeItem) {
            $average = 0;
            foreach ($wheather as $key => $wheatherDataItem) {
//                if ($key > 0 && $key != count($wheather)) {
                $value = $wheatherDataItem['data']->{$averageItemName};
                $average += $value;
//                }
            }

            $averages[$averageItemName] = round($average / $averageItemCount, 2);
        }


        return $averages;

    }

    private function copyFile($s1, $s2)
    {
        $path = pathinfo($s2);
        if (!file_exists($path['dirname'])) {
            mkdir($path['dirname'], 0777, true);
        }
        if (!copy($s1, $s2)) {
            echo "copy failed \n";
        }
    }

    private function getGpxFileFromToken()
    {
        $this->checkLimits();

        $jinput = JFactory::getApplication()->input;
        $token = $jinput->getString('token', '');
        $this->fileUploadPath = 'media/com_roadbikelife/ridewheather/gpxuploads/' . $token . '/';


        $showModel = $this->getShowModel();
        $showModel->setToken($token);
        $this->item = $showModel->getItem();


        $fileUploadPathGpx = $this->fileUploadPath . $this->item->gpx_file;
        $gpxFile = file_get_contents($fileUploadPathGpx);
        $this->gpxFile = new SimpleXMLElement($gpxFile);
        $this->gpxFileName = $this->item->gpx_file;


        $this->setToken();
        $fileUploadPathNew = 'media/com_roadbikelife/ridewheather/gpxuploads/' . $this->token . '/';

        $this->copyFile($fileUploadPathGpx, $fileUploadPathNew . $this->gpxFileName);
        $this->fileUploadPath = $fileUploadPathNew;

    }

    private function getGpxFile($file)
    {
        $fileUploadPathGpx = $this->fileUploadPath . $file['name'];
        $this->upload($file['tmp_name'], $fileUploadPathGpx);

        $gpxFile = file_get_contents($fileUploadPathGpx);
        $this->gpxFile = new SimpleXMLElement($gpxFile);
        $this->gpxFileName = $file['name'];
    }


    public function getPolyline($fileAsXml)
    {

        require_once __DIR__ . '/PolylineEncoder.php';
        $polylineEncoder = new PolylineEncoder();
        $polyCount = $fileAsXml->trk->trkseg->trkpt->count();

        $i = 0;
        foreach ($fileAsXml->trk->trkseg->{'trkpt'} as $key => $trkpt) {
            $i++;
            $polyCount > 50 ? $quotient = (int)$polyCount / 50 : $quotient = 1;
            if ($i % $quotient == 0 || $i == 1 || $i == $polyCount) {
                $trkptlat = (float)$trkpt->attributes()->lat;
                $trkptlon = (float)$trkpt->attributes()->lon;
                $points[] = $trkptlat;
                $polylineEncoder->addPoint($trkptlat, $trkptlon);
            }
        }

        $polyline = $polylineEncoder->encodedString();
        $this->polyline = $polyline;

        return $polyline;
    }

    private function createImageFromFile($filename, $use_include_path = false, $context = null, &$info = null)
    {
        // try to detect image informations -> info is false if image was not readable or is no php supported image format (a  check for "is_readable" or fileextension is no longer needed)
        $info = array("image" => getimagesize($filename));
        $info["image"] = getimagesize($filename);
        if ($info["image"] === false) throw new InvalidArgumentException("\"" . $filename . "\" is not readable or no php supported format");
        else {
            $imageRes = imagecreatefromstring(file_get_contents($filename, $use_include_path, $context));
            if (isset($http_response_header)) $info["http"] = $http_response_header;
            return $imageRes;
        }
    }

    public function getGmapsImageUrl($size, $polyline)
    {

        $imageUrl = RoadbikelifeHelper::getParam('google_maps_staticapi_url') . '?key=' . RoadbikelifeHelper::getParam('google_maps_apiKey') . '&size=' . $size . '&path=color:0xff0000ff|weight:3|enc:' . $polyline;
        return $imageUrl;
    }

    private function createGmapsImage($polyline = null)
    {
        if (!isset($polyline)) {
            $polyline = $this->getPolyline($this->gpxFile);
        }

        $imageUrl = $this->getGmapsImageUrl('200x200', $polyline);

        $sourceImage = self::createImageFromFile($imageUrl);

        imagepng($sourceImage, $this->fileUploadPath . '/gpx_staticmap.png');
        $imageUrl = $this->getGmapsImageUrl('600x300', $polyline);
        $sourceImage = self::createImageFromFile($imageUrl);
        imagepng($sourceImage, $this->fileUploadPath . '/gpx_staticmap_lg.png');

    }

    public function edit()
    {
        $app = JFactory::getApplication();
        $jinput = $app->input;

        $this->setRideStartDate($jinput);
        $this->setRideSpeed($jinput);


        $this->getGpxModel();
        $this->getGpxFileFromToken();
        $this->createGmapsImage();


        if (isset($this->item)) {
            $this->save();
            $app->redirect(JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=show', false) . '/' . $this->token);
            exit();
        }

    }

    public function gpxUpload()
    {
        $this->checkLimits();
        $app = JFactory::getApplication();


        $jinput = $app->input;
        $file = $jinput->files->get('gpxfile');

        $this->setToken();
        $this->setRideStartDate($jinput);
        $this->setRideSpeed($jinput);

        $this->fileUploadPath = 'media/com_roadbikelife/ridewheather/gpxuploads/' . $this->token . '/';

        $this->getGpxModel();
        $this->getGpxFile($file);
        $this->createGmapsImage();


        if (isset($file)) {
            $this->save();
            $app->redirect(JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=show', false) . '/' . $this->token);
            exit();
        }

        exit();
    }


    public function stravaUpload()
    {
        $this->checkLimits();
        $app = JFactory::getApplication();


        $jinput = $app->input;
        $routeId = $jinput->get('strava_route', '0', 'string');

        $this->setToken();
        $this->setRideStartDate($jinput);
        $this->setRideSpeed($jinput);
        $this->getGpxModel();

        $this->fileUploadPath = 'media/com_roadbikelife/ridewheather/gpxuploads/' . $this->token . '/';
        $route = $this->getStravaRoute($routeId);
        $gpxFileContents = $this->gpxModel->createGpxFile($route);
        $this->gpxFileName = str_replace('/', '_', $route->name) . '.gpx';

        if (!file_exists($this->fileUploadPath)) {
            jimport('joomla.filesystem.folder');
            JFolder::create($this->fileUploadPath);
        }
        file_put_contents($this->fileUploadPath . $this->gpxFileName, $gpxFileContents);

        $this->gpxFile = new SimpleXMLElement($gpxFileContents);
        $this->gpxFileName = $this->gpxFileName;
        $this->polyline = $route->map->summary_polyline;

        $this->createGmapsImage($route->map->summary_polyline);


        if (isset($route)) {
            $this->save();
            $app->redirect(JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=show', false) . '/' . $this->token);
            exit();
        }

        exit();
    }

    /**
     * @param mixed $gpxFileName
     */
    public function setGpxFileName($gpxFileName): void
    {
        $this->gpxFileName = $gpxFileName;
    }


}
