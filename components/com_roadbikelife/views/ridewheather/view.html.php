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

jimport('joomla.application.component.view');

/**
 * View class for a list of Roadbikelife.
 *
 * @since  1.6
 */
class RoadbikelifeViewRidewheather extends JViewLegacy
{

    public function display($tpl = null)
    {
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task', '0', 'raw');
        $stravaLogin = $jinput->get('strava_login', '0', 'int');
        $tpl = $this->_prepareDocument($task,$stravaLogin);
        parent::display($tpl);

    }


    protected function _prepareDocument($task,$stravaLogin)
    {
        $doc = JFactory::getDocument();
        $this->model = $this->getModel();


        switch ($task) {
            case 'register':
                $tpl = 'register';
                JLoader::register('RoadbikelifeModelRegister', JPATH_ROOT . '/components/com_roadbikelife/models/register.php');
                $registerModel = new RoadbikelifeModelRegister();
                $registerModel->checkUserLimit();
                $this->form = $registerModel->getForm();

                break;
            case 'login':
                $tpl = 'login';
                $user = JFactory::getUser();
                if(intval($user->id) > 0 ) {
                    $hasAccess = $this->model->checkUserGroupAllowed($user);
                    if($hasAccess === true) {
                        JFactory::getApplication()->redirect( JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=create'));
                    }
                }

                break;
            case 'create':

                $tpl = 'create';
                $this->form = $this->getModel()->getForm();
                $this->stravaLoginButtonUrl = $this->model->getStravaLoginButtonUrl();
                if($stravaLogin === 1) {
                    $this->stravaRoutes = $this->model->getStravaLogin();
                }
                $this->stravaRoutes = $this->model->getStravaRoutes();

                break;
            case 'show':
                $tpl = 'show';

                JLoader::register('RoadbikelifeModelRidewheathershow', JPATH_COMPONENT . '/models/ridewheather_show.php');
                $showModel = new RoadbikelifeModelRidewheathershow();
                $this->showModel = $showModel;
                $this->clothingItems = $showModel->clothingItems;
                $this->averageItems = $showModel->averageItems;
                $this->flotGraphs = $showModel->flotGraphs;
                $this->item = $showModel->getItem();
                $this->item = $showModel->formatWheather();
//                $this->item = $showModel->getRoute();
                $this->form = $this->getModel()->getForm();
	            $showModel->addScripts();


                break;
            case 'list':
                $tpl = 'list';

                JLoader::register('RoadbikelifeModelRidewheathershow', JPATH_COMPONENT . '/models/ridewheather_show.php');
                $showModel = new RoadbikelifeModelRidewheathershow();
                $this->averageItems = $showModel->averageItems;
                $this->items = $showModel->getItems();
                $this->limitStatus = $this->model->getLimitStatus();
                $this->form = $this->getModel()->getForm();
                break;
            default:
                break;

        }

        return $tpl;

    }

}
