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

jimport('joomla.application.component.controller');

use \Joomla\CMS\Factory;

/**
 * Class RoadbikelifeController
 *
 * @since  1.6
 */
class RoadbikelifeController extends \Joomla\CMS\MVC\Controller\BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
     * @throws Exception
	 */
	public function display($cachable = false, $urlparams = false)
	{
        $app  = Factory::getApplication();
        $view = $app->input->getCmd('view', '');
		$app->input->set('view', $view);
        $task = $app->input->getCmd('task', '');

        $model = $this->getModel('roadbikelife');
        $hasAccess = $model->checkUserGroupAllowed(JFactory::getUser());
        $unallowedViewTasks = 'ridewheather.create ridewheather.list';

        if(
            strpos($unallowedViewTasks,"$view.$task") === false
            || $hasAccess === true
        ) {
            parent::display($cachable, $urlparams);
            return $this;
        } else {
            $link = JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=login');
            $msg = 'Du bist nicht berechtigt rideWheather zu nutzen. ';
            $msg.= 'Bitte melde dich mit Deinem Passwort an.';
            $app->redirect($link, $msg, $msgType='warning');
            exit;
        }


	}

    public function backup() {
        $app = JFactory::getApplication('site');
        $password = JFactory::getApplication()->input->get('secret', '', 'STRING');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, JURI::base() . 'index.php?option=com_akeeba&view=Backup&key=' . $password);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch,CURLOPT_MAXREDIRS, 10000);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'api');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10);
        curl_exec($ch);
        curl_close($ch);
        exit('poked!');
    }
}
