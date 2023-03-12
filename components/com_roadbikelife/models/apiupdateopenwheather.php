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
JLoader::register('StravaApi', JPATH_ROOT . '/components/com_roadbikelife/models/includes/StravaApi.php');
JLoader::register('RoadbikelifeModelApiupdatestrava', JPATH_ROOT . '/components/com_roadbikelife/models/apiupdatestrava.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelApiupdateopenwheather extends RoadbikelifeModelRoadbikelife
{


    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */

    private StravaApi $stravaApi;
    private $iconMap = [
        ''
    ];

    public function __construct($config = [])
    {
        parent::__construct($config);

    }

    private function getWheatherData($activity)
    {


        $curl = curl_init();
        $apiKey = RoadbikelifeHelper::getParam('openwheather_apiKey');
        $timeStream = $activity->streams->time;
        $activityLatLng = $activity->streams->latlng;
        $wheatherData = [];

        foreach ($activityLatLng as $key => $latlng) {
            if ($key % 200 == 0) {
                $time = $timeStream[$key] + strtotime($activity->start_date);
                $url = "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$latlng[0]&lon=$latlng[1]&dt=$time&only_current={true}&lang=de&units=metric&appid=$apiKey";

                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_SSL_VERIFYPEER => false

                ]);

                $response = json_decode(curl_exec($curl));
                $err = curl_error($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                    exit();
                } else {

                    $wheatherData['filtered'][] = [
                        'icon'        => $response->current->weather[0]->icon,
                        'windSpeed'   => $response->current->wind_speed,
                        'windBearing' => $response->current->wind_deg,
                    ];

                    $wheatherData['original'][] = $response;

                }
            }
        }

        return $wheatherData;


    }

    public function update()
    {
        $stravaAcitivties = $this->getStravaActivites();
        $output = [];

        foreach ($stravaAcitivties as $activity) {


            $wheatherData = $this->getWheatherData($activity);
            if(isset($wheatherData['filtered'][0]['icon'])) {
                $wheatherDataJson = json_encode($wheatherData['filtered']);

                $data = new stdClass();
                $data->id = $activity->id;
                $data->wheather_json = $wheatherDataJson;
                $data->original_data = json_encode($wheatherData['original']);
                $data->created = date("Y-m-d H:i:s");

                $this->saveWheather($data);

                $output[] = $wheatherData;
            }


        }
        echo '<pre>' , var_dump($output) , '</pre>';
        exit();


    }

    private function saveWheather($data) {
        $tableName             = '#__strava_activity_open_wheather';
        $db = JFactory::getDbo();
        try
        {
            $query  = $db->getQuery(true);
            $result = JFactory::getDbo()->insertObject($tableName, $data);
        }
        catch (Exception $e)
        {
            $result = JFactory::getDbo()->updateObject($tableName, $data, 'id');
        }
    }


    private function getStravaActivites()
    {
        $stravaUpdateModel = new RoadbikelifeModelApiupdatestrava();
        $stravaUpdateModel->setStravaApi();
        $stravaUpdateModel->athenticate();
        $api = $stravaUpdateModel->stravaApi;
        $activites = $api->get('athlete/activities', ['per_page' => 5, [], 'after' => strtotime('now - 4 days')]);
        foreach ($activites as &$activity) {
            $stravaActivityStreams = $api->get("activities/$activity->id/streams", ['keys' => 'latlng,time', 'resolution' => 'medium']);
            $activity->streams = new stdClass();
            $activity->streams->latlng = $stravaActivityStreams[0]->data;
            $activity->streams->time = $stravaActivityStreams[2]->data;
        }

        return $activites;

    }


    private function throwErrors($response)
    {
        if (isset($response->errors)) {
            echo json_encode($response);
            exit;
        }
    }
}
