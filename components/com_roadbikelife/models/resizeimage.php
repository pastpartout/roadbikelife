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


require_once JPATH_ROOT . '/components/com_roadbikelife/vendor/autoload.php';




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

    public static function resizeImage( string $file, int|string|null $width = 0, int|string|null $height = 0, int $webP = 1, int $sharpenImage = 0, int $imageQuality = 95 ): string
    {
        $zbImage                               = new Zebra_Image();
        $fileAbsPath = JPATH_SITE . '/' . $file;
        if (!is_file($fileAbsPath)) {
            return $file;
        } else {
            $app             = Factory::getApplication();
            $cacheFolderName = 'images';
            $name            = pathinfo($file, PATHINFO_FILENAME);
            $imageVersion    = filemtime($fileAbsPath);
            $name            = $name . '_' . $width . '_' . $height . '_' . $sharpenImage . '_' . $imageQuality . '_' . $imageVersion;
            $webP === 1 ? $ext = 'webp' : $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $joomlaCacheFolder = JPATH_ROOT . '/cache';
            $destination       = $joomlaCacheFolder . '/' . $cacheFolderName . '/' . $name . '.' . $ext;
            $newFolderName     = pathinfo($destination, PATHINFO_DIRNAME);

            if (!is_dir($newFolderName)) mkdir($newFolderName, 0755, true);

            if (!is_file($destination)) {
                if ($width == 0) $width = null;
                if ($height == 0) $height = null;

                $zbImage->source_path                  = $fileAbsPath;
                $zbImage->target_path                  = $destination;
                $zbImage->auto_handle_exif_orientation = true;
                $webP === 1 ? $zbImage->webp_quality = $imageQuality : $zbImage->jpeg_quality = $imageQuality;
                $zbImage->sharpen_images = (int)$sharpenImage;
                $zbImage->resize((int)$width, (int)$height, ZEBRA_IMAGE_NOT_BOXED);
            }

            $output = str_replace(JPATH_ROOT . '/', '', $destination);

            if (!isset($zbImage) || $zbImage->error === 1) {
                $output = $file;
            }

            return \JUri::root() . $output;
        }


    }




}

