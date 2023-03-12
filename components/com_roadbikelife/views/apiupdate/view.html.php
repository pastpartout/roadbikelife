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
	    $this->stravaActivityStreams = ['activity','marker','segments','wheather','photos','segments','segment_efforts'];
	    $idsToLoad = [];
	    foreach ($this->items  as $item) {
	    	$idsToLoad[] = $item->strava_id;
	    }

	    if(count($idsToLoad)>0) {
		    $db    = JFactory::getDbo();
		    $query = $db->getQuery(true);
		    $query->select('sas.*,c.title as article_title,w.wheather_json');
		    $query->from($db->quoteName('#__strava_activity_stats','sas'));
		    $query->join('LEFT', $db->quoteName('#__fields_values', 'fv') . ' ON fv.value = sas.id');
		    $query->join('LEFT', $db->quoteName('#__content', 'c') . ' ON c.id = fv.item_id');
		    $query->join('LEFT', $db->quoteName('#__strava_activity_wheather', 'w') . ' ON w.id = sas.id');
		    $query->where('sas.id IN ('.implode(',',$idsToLoad).')');
		    $query->order('c.created DESC');
		    $db->setQuery($query);
		    $this->items = $db->loadObjectList();
		    foreach ($this->items  as &$item)
		    {
			    $contentIds[] = $item->item_id;
			    $contentTitles[] = $item->article_title;
			    $item->activity = json_decode($item->activity_json);
		    }
		    $this->contentIds = array_unique($contentIds);
		    $this->contentTitles = array_unique($contentTitles);
	    }






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
