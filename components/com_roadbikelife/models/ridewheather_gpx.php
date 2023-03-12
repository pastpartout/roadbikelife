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

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');

use phpGPX\Models\GpxFile;
use phpGPX\Models\Link;
use phpGPX\Models\Metadata;
use phpGPX\Models\Point;
use phpGPX\Models\Segment;
use phpGPX\Models\Track;
require_once JPATH_BASE.'/vendor/autoload.php';

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelRidewheathergpx extends RoadbikelifeModelRoadbikelife
{
    public function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {

        $rad = M_PI / 180;
        //Calculate distance from latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin($latitudeFrom * $rad)
            * sin($latitudeTo * $rad) + cos($latitudeFrom * $rad)
            * cos($latitudeTo * $rad) * cos($theta * $rad);

        return acos($dist) / $rad * 60 * 1.853;
    }


    public function getDistance($fileAsXml)
    {
        $last_lat = false;
        $last_lon = false;
        $total_distance = 0;

        foreach ($fileAsXml->trk->trkseg->{'trkpt'} as $trkpt) {
            $trkptlat = (float)$trkpt->attributes()->lat;
            $trkptlon = (float)$trkpt->attributes()->lon;
//		    var_dump();
            if ($last_lat) {
                $newDistance = self::distance($trkptlat, $trkptlon, $last_lat, $last_lon);
                $total_distance += $newDistance;
            }

            $last_lat = $trkptlat;
            $last_lon = $trkptlon;
        }
//	    exit;
        return round($total_distance, 0);


    }

    public function getElevation($fileAsXml)
    {
        $total_elevation = 0;
        $last_elevation = 0;
        foreach ($fileAsXml->trk->trkseg->{'trkpt'} as $trkpt) {
            $elevation = (float)$trkpt->ele;
            if ($last_elevation == 0) $last_elevation = $elevation;

            if ($elevation < $last_elevation) {
                $total_elevation += ($last_elevation - $elevation);
            }
            $last_elevation = $elevation;
        }
        return round($total_elevation, 0);
    }


    public function getTitle($fileAsXml)
    {
        return (string)$fileAsXml->trk->name;
    }


    public function getStartCity($latitude, $longitude)
    {
        if (!empty($latitude) && !empty($longitude)) {
            //Send request and receive json data by address
            $geocodeFromLatLong = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC3j1YXb_4BRQBrtMewqKatGGRsQ69XAO8&latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=false');
            $output = json_decode($geocodeFromLatLong);

            $status = $output->status;
            //Get address from json data
            $address = ($status == "OK") ? $output->results[1]->formatted_address : '';
            //Return address of the given latitude and longitude

            if (!empty($address)) {
                return $address;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function createImageFromFile($filename, $use_include_path = false, $context = null, &$info = null)
    {
        // try to detect image informations -> info is false if image was not readable or is no php supported image format (a  check for "is_readable" or fileextension is no longer needed)
        $info = array("image" => getimagesize($filename));
        $info["image"] = getimagesize($filename);
        if ($info["image"] === false) throw new InvalidArgumentException("\"" . $filename . "\" is not readable or no php supported format");
        else {
            // fetches fileconten from url and creates an image ressource by string data
            // if file is not readable or not supportet by imagecreate FALSE will be returnes as $imageRes
            $imageRes = imagecreatefromstring(file_get_contents($filename, $use_include_path, $context));
            // export $http_response_header to have this info outside of this function
            if (isset($http_response_header)) $info["http"] = $http_response_header;
            return $imageRes;
        }
    }

    function save_image($inPath, $outPath)
    { //Download images from remote server
        $in = fopen($inPath, "rb");
        $out = fopen($outPath, "wb");
        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }



    public function createGpxFile($route)
    {
        $app = JFactory::getApplication();
        $speed = $app->input->get('speed', 0) / 3.6;
        $distance = $route->distance;
        $timeSegment = round($distance / $speed, 0);


        // Creating sample link object for metadata
        $link = new Link();
        $link->href = JURI::base();
        $link->text = 'roadbikelife.de';

// GpxFile contains data and handles serialization of objects
        $gpx_file = new GpxFile();

// Creating sample Metadata object
        $gpx_file->metadata = new Metadata();
// Time attribute is always \DateTime object!
        $gpx_file->metadata->time = new \DateTime();
// Description of GPX file
        $gpx_file->metadata->description = "roadbikelife.de Blog GPX Download: $route->name";
// Adding link created before to links array of metadata
// Metadata of GPX file can contain more than one link
        $gpx_file->metadata->links[] = $link;
        $track = new Track();
        $track->name = sprintf($route->name);
        $track->type = 'RIDE';
// Source of GPS coordinates
        $track->source = sprintf("roadbikelife.de Blog");
        $segment = new Segment();
        $track->segments[] = $segment;

        require_once JPATH_COMPONENT_ADMINISTRATOR . '/models/includes/PolylineEncoder.php';
        $polylineEncoder = new PolylineEncoder();
        $points = $polylineEncoder->decodeValue($route->map->polyline);
        $i = 1;

        foreach ($points as $point) {
            $time += ($timeSegment * $i);
            // Creating trackpoint
            $trackPoint = new Point(Point::TRACKPOINT);
            $trackPoint->latitude = $point['x'];
            $trackPoint->longitude = $point['y'];
            $trackPoint->time = $time;
            $segment->points[] = $trackPoint;

        }



// Recalculate stats based on received data
        $track->recalculateStats();
// Add track to file
        $gpx_file->tracks[] = $track;

// GPX output

        return $gpx_file->toXML()->saveXML();
    }


    public function saveGmapsImage($fileAsXml, $subFolder, $filename)
    {
        $filename = str_replace(array('.GPX', '.gpx'), '', $filename);
        $polyline = $this->getPolyline($fileAsXml);


        $imageUrl = $this->getGmapsImageUrl('200x200', $polyline);

        $sourceImage = self::createImageFromFile($imageUrl);

        imagepng($sourceImage, 'gpx_uploads/' . $subFolder . '/gmaps_' . $filename . '.png');
        $imageUrl = $this->getGmapsImageUrl('600x300', $polyline);
        $sourceImage = self::createImageFromFile($imageUrl);
        imagepng($sourceImage, 'gpx_uploads/' . $subFolder . '/gmaps_' . $filename . '_large.png');
//			echo '<img src="'.$imageUrl.'">';
//			echo 'gpx_uploads/'.$subFolder.'/gmaps.png';

    }

    public function getPolyline($fileAsXml)
    {

        require_once JPATH_COMPONENT . '/models/includes/PolylineEncoder.php';
        $polylineEncoder = new PolylineEncoder();
        $polyCount = $fileAsXml->trk->trkseg->trkpt->count();

        $i = 0;
        foreach ($fileAsXml->trk->trkseg->{'trkpt'} as $key => $trkpt) {
            $i++;
            $polyCount > 50 ? $quotient = (int)$polyCount / 50 : $quotient = 1;
            if ($i % $quotient == 0 || $i == 1 || $i == $polyCount) {
                $trkptlat = (float)$trkpt->attributes()->lat;
                $trkptlon = (float)$trkpt->attributes()->lon;
                $points[] = $trkptlat;
                $polylineEncoder->addPoint($trkptlat, $trkptlon);
            }
        }

        $polyline = $polylineEncoder->encodedString();
        return $polyline;
    }

    protected function getGmapsImageUrl($size, $polyline)
    {

        $imageUrl = $this->options['gmaps_api_url'] . '?key=' . $this->options['gmaps_api_key'] . '&size=' . $size . '&path=color:0xff0000ff|weight:3|enc:' . $polyline;
        return $imageUrl;
    }

    public function getStartLng($fileAsXml)
    {

        return (string)$fileAsXml->trk->trkseg->{'trkpt'}{0}->attributes()->lon;
    }


    public function getStartLat($fileAsXml)
    {
        return (string)$fileAsXml->trk->trkseg->{'trkpt'}{0}->attributes()->lat;
    }
}
