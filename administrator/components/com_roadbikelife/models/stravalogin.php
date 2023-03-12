<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.memberarea');
JLoader::register('RoadbikelifeModel', __DIR__ . '/roadbikelife.php');
//JLoader::register('FrontpageModel', JPATH_SITE. '/libraries/tgrc/FrontpageModel.class.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelStravalogin extends JModelLegacy
{

	private $params;

    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */
    public function __construct($config = array())
    {
//
        parent::__construct($config);
	    $this->params = JComponentHelper::getParams('com_roadbikelife');

    }


    public function getAccessTokenCode()
    {
        $jinput = JFactory::getApplication()->input;
        $code = $jinput->get('code', '', 'RAW');
        return $code;
    }

    public function getStravaLogin()
    {
        JLoader::register('Iamstuartwilson\StravaApi', JPATH_ROOT . '/vendor/iamstuartwilson/strava/src/Iamstuartwilson/StravaApi.php');


        $api = new Iamstuartwilson\StravaApi (
           $this->params->get('strava_clientId'),
	        $this->params->get('strava_clientSecret')
        );

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        $code = $this->getAccessTokenCode();

        if ($code != '') {

            $jinput = JFactory::getApplication()->input;
            $accessToken = $api->tokenExchange($code);

            $api->setAccessToken($accessToken->access_token);
            $stravaMember = $accessToken->athlete;

            // Exit when error on strava login
            if (!isset($stravaMember->id)) {
                $link = JURI::base() . 'index.php?option=com_roadbikelife&view=stravalogin';
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

        $link = JURI::base() . 'index.php?option=com_roadbikelife&view=stravalogin';
        $app->redirect($link, "Strava-Anmeldung erfolgreich! StravaID: $stravaMember->id", 'success');

    }

}
