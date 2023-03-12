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
class RoadbikelifeModelRegister extends RoadbikelifeModelRoadbikelife
{


    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */
    public function save()
    {
        $app = JFactory::getApplication();

        $joomla_captcha = JFactory::getConfig()->get('captcha');
        if ($joomla_captcha != '0') {
            $jpost = JFactory::getApplication()->input->post;
            $reCaptcha = $jpost->get("g-recaptcha-response");
        }

        if (isset($reCaptcha) && empty($reCaptcha)) {
            die("Invalid Captcha");
        }

        $userSaved = $this->saveUser();
        if ($userSaved !== false) {
            $this->loginUser($userSaved);
        }

        $this->queueErrors();
        $app->redirect(JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=create', false));
    }

    protected function saveUser()
    {
        $jinput = JFactory::getApplication()->input;
        $email = $jinput->get('email', null, 'string');

        $tableName = '#__users';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('COUNT(*)')
            ->from($db->quoteName($tableName))//Articles table
            ->where([
                $db->quoteName('email') . ' = ' . $db->quote($email),
            ]);
        $db->setQuery($query);

        // Dont insert existing email at first time
        if (!isset($tokenItem->user_id)) {
            if ((int)$db->loadResult() > 0) {
                $error = 'Diese E-Mail-Adresse ist bereits registriert';
                array_push($this->errors, $error);
                return false;
            }
        }


        $fields = new stdClass();
        $fields->name = $jinput->get('prename', null, 'string') . ' ' . $jinput->get('lastname', null, 'string');
        $fields->email = $email;
        $fields->username = $fields->email;
        $password = $jinput->get('password', null, 'string');
        if (isset($password)) {
            $fields->password = $password;
        }
        $fields->groups = [1, 10];

        $user = new JUser;
        $fieldsArray = (array)$fields;
        $user->bind($fieldsArray);
        if (!$user->save()) {
            $error = $user->getError();
            JFactory::getApplication()->enqueueMessage($error, 'danger');
            return $error;
        } else {
            $fields->id = $user->id;
            $user = JFactory::getUser();

            $mailer = JFactory::getMailer();
            $config = JFactory::getConfig();
            $sender = array(
                $config->get( 'mailfrom' ),
                $config->get( 'fromname' )
            );

            $mailer->setSender($sender);
            $mailer->addRecipient('info@roadbikelife.de');
            $body = "
            Neue Registrierung auf roadbikelife.de 
            \n
            \n
            Name: $user->name \n
            E-Mail: $user->email
            
            ";
            $mailer->setSubject('Neue Registrierung auf roadbikelife.de');
            $mailer->setBody($body);
            $send = $mailer->Send();
            if ( $send !== true ) {
                echo 'Error sending email: ';
            }
            return $fields;
        }

    }

    public function checkUserLimit()
    {
        $tableName = '#__users';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('COUNT(*)')
            ->from($db->quoteName($tableName));
        $db->setQuery($query);
        $userCount = (int)$db->loadResult();
        if ($userCount > 20) {
            JFactory::getApplication()->redirect(JUri::base(), 'Sorry! Maximale Anzahl an Testusern ist bereits erreicht', 'danger');
            return false;
            exit;
        }
    }

    protected function loginUser($user)
    {

        if (!isset($user)) {
            return false;
        }

        $app = JFactory::getApplication();
        if ($user->password != '') {
            $app->logout();
            JFactory::getUser();

            $credentials = array('username' => $user->username, 'password' => $user->password);
            $result = $app->login($credentials, array('silent' => true, 'action' => 'roadbikelife.login'));
            if ($result === false && $user->password != '') {
                $error = 'Der User konnte nicht angemeldet werden.';
                array_push($this->errors, $error);
                return false;
            }
        }
    }

    protected function queueErrors()
    {
        if (count($this->errors) < 1) {
            return false;
        } else {
            foreach ($this->errors as $error) {
                JFactory::getApplication()->enqueueMessage($error, 'danger');
            }
            return true;
        }
    }

    public function getForm()
    {
        $form = JForm::getInstance('com_roadbikelife.user', 'components/com_roadbikelife/models/forms/ridewheather_registrer.xml');

        if (empty($form)) {
            return false;
        }

        return $form;
    }


}
