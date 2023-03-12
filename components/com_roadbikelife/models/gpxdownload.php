<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use phpGPX\Models\GpxFile;
use phpGPX\Models\Link;
use phpGPX\Models\Metadata;
use phpGPX\Models\Point;
use phpGPX\Models\Segment;
use phpGPX\Models\Track;

require_once JPATH_BASE . '/vendor/autoload.php';


jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelGpxdownload extends RoadbikelifeModelRoadbikelife
{


	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */

	private $mkDirMode = 0775;

	public function __construct($config = array())
	{

		parent::__construct($config);

	}

	public function getGpxFileDownload()
	{
		$app              = JFactory::getApplication();
		$input            = $app->input;
		$stravaActivityId = $input->getInt('strava_activity_id', '0');
		$config = new JConfig();

		if ($stravaActivityId > 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('sas.*');
			$query->from($db->quoteName('#__strava_activity_stats', 'sas'));
			$query->where($db->quoteName('sas.id') . ' = ' . $db->quote($stravaActivityId));
			$db->setQuery($query);
			$stravaActivity = $db->loadObject();

			if (isset($stravaActivity))
			{

				$stravaActivityJson      = json_decode($stravaActivity->activity_json);
				$stravaActivityLatLng    = json_decode($stravaActivity->latlng_json);
				$stravaActivityElevation = json_decode($stravaActivity->altitude_json);
				$stravaActivityTime      = json_decode($stravaActivity->time_json);

				$app               = JFactory::getApplication();
				$distance          = $stravaActivityJson->distance;
				$distanceFormatted = RoadbikelifeHelper::formatRenderedValue($distance,'distance', true,true);
				$elevationFormatted = RoadbikelifeHelper::formatRenderedValue($stravaActivityJson->total_elevation_gain,'total_elevation_gain', true,true);

				$speed             = $stravaActivityJson->average_speed;
				$timeSegment       = round($distance / $speed, 0);

				// Creating sample link object for metadata
				$link       = new Link();
				$link->href = JURI::base();
				$link->text = 'roadbikelife.de - Rennrad-Blog aus Leipzig';
				$link->type = 'text/html';

				$author = new \phpGPX\Models\Person();

				$author->name = "Stephan Riedel";
				$author->link = $link;
				$gpx_file = new GpxFile();

				$gpx_file->metadata              = new Metadata();
				$gpx_file->metadata->time        = new \DateTime();
				$gpx_file->creator               = $author;
				$gpx_file->metadata->author      = $author;
				$gpx_file->metadata->creator     = $author;
				$gpx_file->metadata->name        = "$stravaActivityJson->name - roadbikelife.de Route";
				$gpx_file->metadata->description = "Distanz: $distanceFormatted, Anstieg: $elevationFormatted";
				$gpx_file->metadata->links[]     = $link;


				$track         = new Track();
				$track->name   = $gpx_file->metadata->name;
				$track->type   = 'Roadbike';
				$track->source = sprintf("roadbikelife.de - Rennrad-Blog aus Leipzig");


				$segment           = new Segment();
				$track->segments[] = $segment;


				$points = $stravaActivityLatLng;
				$now    = strtotime('now');

				foreach ($points as $index => $point)
				{
					// Creating trackpoint
					$trackPoint            = new Point(Point::TRACKPOINT);
					$trackPoint->latitude  = $point[0];
					$trackPoint->longitude = $point[1];
					$trackPoint->time      = new \DateTime('now + ' . $stravaActivityTime[$index] . ' SECONDS');
					$trackPoint->elevation = $stravaActivityElevation[$index];
					$segment->points[]     = $trackPoint;
				}

				$track->recalculateStats();
				$track->stats->distance = $stravaActivityJson->distance;
				$track->stats->cumulativeElevationGain = $stravaActivityJson->total_elevation_gain;

				$gpx_file->tracks[] = $track;
				$app->setHeader('Content-Type', 'applicaton/octet-stream;charset=utf-8');
				$app->setHeader('Content-Disposition', "attachment; filename=\"$track->name ($distanceFormatted, $elevationFormatted).gpx\"");
				$app->sendHeaders();
				echo $gpx_file->toXML()->saveXML();
				die();
			}

		}

		$app->redirect($_SERVER['HTTP_REFERER']);

	}


}
