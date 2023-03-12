<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

use Joomla\CMS\Factory;
use phpGPX\Models\GpxFile;
use phpGPX\Models\Link;
use phpGPX\Models\Metadata;
use phpGPX\Models\Point;
use phpGPX\Models\Segment;
use phpGPX\Models\Track;
require_once JPATH_BASE.'/vendor/autoload.php';


jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');
/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelGpxexport extends RoadbikelifeModelRoadbikelife
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

		parent::__construct($config);

    }

    public function getgpxFile()
    {
        $input = JFactory::getApplication()->input;
        $file = $input->getString('file');
        $app = JFactory::getApplication();

        if(isset($file)) {
            $fileContents = file_get_contents($file);

            if(isset($file) && strpos($fileContents,'<extensions>') > 0) {
//                $fileContents = trim(preg_replace('/\s+/', '', $fileContents));
                $fileContents = preg_replace('/<extensions>.*?<\/extensions>/s','',$fileContents);
            }

            header("Content-Type: application/gpx+xml");
            header("Content-Disposition: attachment; filename=".basename($file));
            echo $fileContents;
            exit;
        } else {
           $app->redirect(JURI::base(),'Sorry! GPX-Datei nicht gefunden','warning');
        }

    }




}
