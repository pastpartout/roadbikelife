<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');

/**
 * Logins list controller class.
 *
 * @since  1.6
 */

class RoadbikelifeControllerApiupdate extends RoadbikelifeController
{
    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional
     * @param   array   $config  Configuration array for model. Optional
     *
     * @return object	The model
     *
     * @since	1.6
     */


    public function &getModel($name = 'Apiupdate', $prefix = 'RoadbikelifeModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;
    }

    private function checkAccess() {
        $jinput = JFactory::getApplication()->input;
        $getSecret = $jinput->get('secret', '', 'string');

        $secretToMatch = RoadbikelifeHelper::getParam('urlToken');

        if($secretToMatch != $getSecret) {
            exit('FORBIDDEN');
        }

	    return $secretToMatch;

    }

//    public function facebook()
//    {
//        $this->checkAccess();
//        $this->getModel('Apiupdatefacebook')->update();
////        parent::display();
//
//    }
//
//    public function dropbox()
//    {
//        $this->checkAccess();
//        $this->getModel('Apiupdatedropbox')->export();
////        parent::display();
//
//    }

    public function strava() {
        $secretToMatch = $this->checkAccess();
        $params = JFactory::getApplication()->getTemplate(true)->params;
        $url = JUri::base() . 'component/roadbikelife/apiupdate/runstrava/'.$secretToMatch;
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, TRUE); // We'll parse redirect url from header.
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); // We want to just get redirect url but not to follow it.
	    curl_setopt($ch, CURLOPT_USERAGENT, 'api');
	    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch,  CURLOPT_RETURNTRANSFER, false);
	    curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
	    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10);
	    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_exec($ch);
        exit('poked!');
    }

    public function openwheather()
    {
        $this->checkAccess();
        $this->getModel('Apiupdateopenwheather')->update();

    }

    public function wheather()
    {
        $this->checkAccess();
        $this->getModel('Apiupdatewheather')->update();

    }

    public function runstrava()
    {
	    $this->checkAccess();
	    parent::display();

    }

    public function instagram()
    {
        $this->checkAccess();
        $this->getModel('ApiupdateInstagram')->update();
//        parent::display();

    }

    public function deleteunusedgpxuploads()
    {
        $this->checkAccess();
        $this->getModel('ApiupdateDeleteUnusedGpxUploads')->run();
//        parent::display();

    }



}
