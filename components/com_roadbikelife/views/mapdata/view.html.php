<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Roadbikelife.
 *
 * @since  1.6
 */
class RoadbikelifeViewMapdata extends JViewLegacy
{

	public function display($tpl = null)
	{
		JLoader::register('modArticleStravaMap', JPATH_BASE . '/modules/mod_article_stravamap/helper/mod_article_stravamap.php');
		$modArticleStravaMap = new modArticleStravaMap();
		$this->data = $modArticleStravaMap->getStravaActivityJson();
		parent::display($tpl);

	}
}
