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
JLoader::register('RoadbikelifeModelApiupdatestrava', JPATH_ROOT . '/components/com_roadbikelife/models/apiupdatestrava.php');

/**
 * View class for a list of Roadbikelife.
 *
 * @since  1.6
 */
class RoadbikelifeViewApiupdate extends JViewLegacy
{

    public function display($tpl = null)
    {
	    $time_start = microtime(true);

	    $stravaUpdateModel = new RoadbikelifeModelApiupdatestrava();
	    $this->items = $stravaUpdateModel->update();
//	    $this->stravaActivityStreams = $stravaUpdateModel->streamFields;
//	    $this->stravaActivityExtraFields = ['activity','segment_efforts','photos'];

	    JHtml::_('jquery.framework');
	    $doc =  JFactory::getDocument();
	    $doc->addScript('/components/com_roadbikelife/views/apiupdate/js/json-viewer/jquery.json-viewer.js');
	    $doc->addScript('/components/com_roadbikelife/views/apiupdate/js/json-viewer/init.js');
	    $doc->addStyleSheet('/components/com_roadbikelife/views/apiupdate/js/json-viewer/jquery.json-viewer.css');

	    // Display Script End time
	    $time_end = microtime(true);
	    $this->execution_time = ($time_end - $time_start);

	    parent::display($tpl);

    }
}
