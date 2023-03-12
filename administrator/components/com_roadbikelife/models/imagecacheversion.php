<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;




jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelImagecacheversion extends JModelLegacy
{




    public function __construct($config = array())
    {

        parent::__construct($config);

    }

    public function update()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('params');
        $query->from($db->quoteName('#__extensions'));
        $query->where($db->quoteName('name') . ' = '.$db->quote('com_roadbikelife'));
        $db->setQuery($query);
        $configParams = json_decode( $db->loadResult());
        $configParams->{imagecacheversion} = (int)$configParams->imagecacheversion + 1;

        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__extensions'));
        $query->set($db->quoteName('params') . ' = ' . $db->quote(json_encode($configParams)));
        $query->where($db->quoteName('name') . ' = '.$db->quote('com_roadbikelife'));
        $db->setQuery($query);
        $db->execute($query);
        $url = $_SERVER['HTTP_REFERER'];
        JFactory::getApplication()->redirect($url);


    }


}
