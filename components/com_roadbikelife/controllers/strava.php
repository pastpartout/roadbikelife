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

/**
 * Logins list controller class.
 *
 * @since  1.6
 */
class RoadbikelifeControllerStrava extends RoadbikelifeController
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


    public function &getModel($name = 'Login', $prefix = 'RoadbikelifeModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}


    public function success()
    {

        if(isset($_GET['forwardToRegistration'])) {
            $app  = JFactory::getApplication();
            $link = JURI::base().'memberarea/memberregistration/'.$_GET['forwardToRegistration'];
            $app->redirect(
                $link,
                '<strong>Yeah! Die Strava-Autorisierung war erfolgreich.</strong><br>'
                .'Falls du einen Fehler in deinen Angaben entdeckst, kannst du das Formular einfach ändern und erneut speichern.',
                'success'
            );
        }

        parent::display();

        if(!isset($_GET['code'])) {
            $app  = JFactory::getApplication();
            $link = JURI::base().'memberarea/strava';
            $app->redirect($link);
        }
    }

}
