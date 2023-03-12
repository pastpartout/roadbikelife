<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Memberarea
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');
/**
 * Methods supporting a list of Memberarea records.
 *
 * @since  1.6
 */
class RoadbikelifeModelLogin extends RoadbikelifeModelRoadbikelife
{


    /**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
//
		parent::__construct($config);



    }


    public function verifyLogindata()
    {
        JSession::checkToken() or die( 'Invalid Token' );
        $user = Factory::getIdentity();
        $this->user = $user;
        $app = Factory::getApplication();
	    $this->app  = $app;
	    $dispatcher = $app->getDispatcher();

	    $jinput = $this->app->input;
	    $passwordToMatch = $jinput->get('password', '', 'RAW');
	    $username = $jinput->get('username', '', 'RAW');

	    $db = Factory::getDbo();
	    $query = $db->getQuery(true);
	    $query->select('*');
	    $query->from($db->quoteName('#__users'));
	    $query->where($db->quoteName('email') . ' = ' . $db->quote($username));
	    $query->setLimit('1');
	    $db->setQuery($query);
	    $user = $db->loadObject();


	    $password = $user->password;
	    $userId = $user->id;
	    $username = $user->username;


	    $link = preg_replace('/\/$/','',JURI::base()).'/component/roadbikelife/ridewheather/create';


	    if (isset($_POST['password']) && isset($userId)){
            jimport('joomla.user.helper');
            $passwordMatched = JUserHelper::verifyPassword($passwordToMatch,$password,$userId);
        }

        if($passwordMatched == true) {

            $this->app->login(array('username' => $user->username,'password'=>$passwordToMatch), array('silent' => false));
            $userGoupAllowed = self::checkUserGroupAllowed(Factory::getIdentity());

            if($userGoupAllowed == true) {
                $this->memberAccess = true;
                $this->app->redirect($link);
            } else {
                $dispatcher->dispatch('onUserLoginFailure',$this->user);

                $this->app->redirect(JURi::current(),'Du bist nicht berechtigt.','danger');
            }
        } elseif(isset($_POST['password'])) {
            $dispatcher->dispatch('onUserLoginFailure',$this->user);
            $this->app->redirect(JUri::base(),'Das eingegebene Passwort ist nicht korrekt.','danger');
        }
    }


}
