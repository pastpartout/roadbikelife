<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

if (strpos(JURI::base(), 'localhost') > 0 || strpos(JURI::base(), 'roadbikelife') > 0)
{
	ini_set('memory_limit', '4048M');
	ini_set('time_limit', '300');

}
else
{
	ini_set('memory_limit', '1024M');
}

use Joomla\CMS\Factory;


require_once JPATH_ROOT . '/vendor/autoload.php';




/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelResizeimage {
	private $file = '';
	private $width = 500 ;
	private $height = 0;
	private $addWatermark = false;
	private $watermarkPos = 'bottom center';
	private $imageQuality = 85;
	private $webP = 0;
	private $processor = 'zebra_image';
	private $sharpenImage = true;
	private $cacheFolder = 'images';
	private $destination = '';

	public function __construct($config = null)
	{
		$this->setArgs($config);
	}

	private function setArgs($args) {
		if (isset($args))
		{
			foreach ($args as $key => $value)
			{
				if(isset($value)) {
					$this->{$key} = $value;
				}
			}
		}

		if (strpos($this->file, '/') === 0)
		{
			$this->file = substr($this->file, 1);
		}

	}

	public function getResizedImagePath(
		$file, $width = null, $height = 0, $webP = 0, $sharpenImage = null,$cropMode = '', $addWatermark = null, $watermarkPos = null, $imageQuality = null
	)
	{
		$args = get_defined_vars();
		$this->setArgs($args);

		$this->processImage();
		return $this->returnDestination();
	}

	public function returnDestination() {
		$destination = str_replace(JPATH_BASE.'/', '',$this->destination);
		return JURI::base().$destination;
	}

//	public function getResizedImage()
//	{
//		$newFileName = $this->processImage();
//		$this->readFile($newFileName);
//		exit;
//	}

	private function readFile($newFileName)
	{
		$ext = pathinfo($newFileName, PATHINFO_EXTENSION);

		if ($ext === 'webp')
		{
			$type = 'image/webp';
		}
		elseif ($ext === 'jpeg' || $ext === 'jpg')
		{
			$type = 'image/jpeg';
		}

		header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
		header("Pragma: no-cache"); //HTTP 1.0
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

		//or, if you DO want a file to cache, use:
		header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
		header('Content-Type:' . $type);
		header('Content-Length: ' . filesize($newFileName));
		readfile($newFileName);
	}

	private function getImageSize()
	{
		return getimagesize($this->file);
	}

	private function processImage()
	{
		$file = $this->file;

		if (!is_file($file))
		{
			$this->destination = $file;
			return;
		}

		$width         = $this->width;
		$height        = $this->height;
		$destination   = $this->getDestination();
		$newFolderName = pathinfo($destination, PATHINFO_DIRNAME);

		if (!is_dir($newFolderName))
		{
			mkdir($newFolderName, 0755, true);
		}

		if (!is_file($destination))	{
			if ($width == 0)
			{
//				$width = $this->getImageSize()[0];
				$width = null;
			}
			if ($height == 0)
			{
//				$height = $this->getImageSize()[1];
				$height = null;

			}

				if (extension_loaded('imagick') && $this->processor === 'imagick')
				{
					$imagick = new \Imagick(JPATH_BASE . '/' . $file);
					$imagick->adaptiveResizeImage($width, $height, true);
					if($this->sharpenImage) {
						$imagick->sharpenImage(0, 1);
					}
					$imagick->writeImage(JPATH_BASE . '/' . $destination);

				}
				elseif ($this->processor === 'zebra_image')
				{
					$zbImage               = new Zebra_Image();
					$zbImage->source_path  = $file;
					$zbImage->target_path  = $destination;
					$zbImage->auto_handle_exif_orientation = true;
					$zbImage->jpeg_quality = $this->imageQuality;
					$zbImage->webp_quality = $this->imageQuality;
					$zbImage->sharpen_images = $this->sharpenImage;
					$zbImage->resize($width, $height, ZEBRA_IMAGE_NOT_BOXED);
				}

		}

		$this->destination =  $destination;
	}

	private function getDestination()
	{
		$app = Factory::getApplication();
		$file         = $this->file;
		$width        = $this->width;
		$height       = $this->height;
		$addWatermark = $this->addWatermark;
		$watermarkPos = $this->watermarkPos;
		$cacheFolderName  = $this->cacheFolder;
		$isWebP       = $this->webP;

		$name = pathinfo($file, PATHINFO_FILENAME);
		$imageVersion = filemtime($file);
		
		$name = crc32( $name.$width.$height.$addWatermark.$watermarkPos.$imageVersion);

		$this->webP  === 1 ? $ext = 'webp' : $ext = pathinfo($file, PATHINFO_EXTENSION);
		$path = pathinfo($file, PATHINFO_DIRNAME);

		$joomlaCacheFolder = $app->get('cache_path', JPATH_CACHE);
		$newFileName = "$joomlaCacheFolder/$cacheFolderName/$name.$ext";

		return $newFileName;
	}



}

