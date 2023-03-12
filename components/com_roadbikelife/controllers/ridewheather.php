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

class RoadbikelifeControllerRidewheather extends RoadbikelifeController
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


    public function &getModel($name = 'Ridewheather', $prefix = 'RoadbikelifeModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;
    }


    public function stravaupload(){
        $this->getModel()->stravaUpload();
        parent::display();
    }

    public function create(){
        parent::display();
    }

    public function show(){
        parent::display();
    }

    public function edit(){
        parent::display();
        $this->getModel()->edit();
    }

    public function list(){
        parent::display();
    }

    public function gpxupload() {
        $this->getModel()->gpxUpload();
    }









}
