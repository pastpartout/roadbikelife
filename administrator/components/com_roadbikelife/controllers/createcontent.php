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
class RoadbikelifeControllerCreatecontent extends RoadbikelifeController
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


    public function &getModel($name = 'Createcontent', $prefix = 'RoadbikelifeModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

    public function save()
    {
        $this->getModel()->save();
        JFactory::getApplication()->redirect(JURI::base().'index.php?option=com_content');

    }

    public function apply() {
        $contentItemSaved = $this->getModel()->save();
        $jinput = JFactory::getApplication()->input;
        $url = JUri::base().'index.php?option=com_roadbikelife&view=createcontent&id='.$contentItemSaved;
        JFactory::getApplication()->redirect($url);

    }

    public function cancel($key = null)
    {
        JFactory::getApplication()->redirect(JURI::base().'index.php?option=com_content');
    }

    public function deleteimagecache()
    {
        $this->getModel()->deleteImageCache();
    }



//    public function look()
//    {
//        parent::display();
//    }



}
