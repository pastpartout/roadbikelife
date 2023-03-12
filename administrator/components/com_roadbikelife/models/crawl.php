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

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelCrawl extends JModelLegacy
{

	const DEPTH = 2;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */


	public function __construct($config = [])
	{

		parent::__construct($config);

	}

	private static function send_message($id, $message) {
		$app = Factory::getApplication();
		$app->setHeader('Content-type','text/event-stream');
		$app->sendHeaders();
		$d = ['message' => $message];

		echo "id: $id" . PHP_EOL;
		echo "data: " . json_encode($d) . PHP_EOL;
		echo PHP_EOL;

		ob_flush();
		flush();
	}


	public static function crawlPage($url, $depth = 5, &$i = 0)
	{
		static $seen = array();
		if (isset($seen[$url]) || $depth === 0) {
			return;
		}

		$seen[$url] = true;

		$i++;
		$dom = new DOMDocument('1.0');
		@$dom->loadHTMLFile($url);
		self::send_message($i, "#$i cached: $url");

		$anchors = $dom->getElementsByTagName('a');
		foreach ($anchors as $element)
		{
			$href = $element->getAttribute('href');
			if (0 !== strpos($href, 'http'))
			{
				$path = '/' . ltrim($href, '/');
				if (extension_loaded('http'))
				{
					$href = http_build_url($url, ['path' => $path]);
				}
				else
				{
					$parts = parse_url($url);
					$href  = $parts['scheme'] . '://';
					if (isset($parts['user']) && isset($parts['pass']))
					{
						$href .= $parts['user'] . ':' . $parts['pass'] . '@';
					}
					$href .= $parts['host'];
					if (isset($parts['port']))
					{
						$href .= ':' . $parts['port'];
					}
					$href .= dirname($parts['path'], 1) . $path;
				}
			}
			self::crawlPage($href, $depth - 1, $i);
		}



		return false;
	}


}
