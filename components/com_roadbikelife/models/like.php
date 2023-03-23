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

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.roadbikelife');
JLoader::register('RoadbikelifeModelRoadbikelife', __DIR__ . '/roadbikelife.php');
/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelLike extends RoadbikelifeModelRoadbikelife
{


    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */


    public $response = '';

    public function __construct($config = array())
    {

        parent::__construct($config);

    }

    public function getLikesIsDisabled($id=false,$type=false) {
//        $session = JFactory::getSession();
//        $givenLikeArray = $session->get("given_likes_$type");
//        if(isset($givenLikeArray)) {
//            foreach ($givenLikeArray as $givenLike) {
//                if($givenLike == $id) {
//                    return true;
//                }
//            }
//        }

        return false;
    }


    public function getCommentsCount(int|string $id) {
        $tableName = '#__comment';
        $app = JFactory::getApplication();

        if ($id !== false && $id !== false) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('COUNT(id)');
            $query->from($db->quoteName($tableName));
            $query->where(array($db->quoteName('published') . ' = 1'));
            $query->where(array($db->quoteName('contentid') . ' = ' . $db->quote($id)));
            $db->setQuery($query);
            $result = $db->loadResult();

            if($result) {
                return (int)$result;
            } else {
                return 0;
            }
        }
    }
    public function getLikesCount($id=false,$type=false) {
        $tableName = '#__likes';
        $app = JFactory::getApplication();

        if ($id !== false && $id !== false) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName($tableName));
            $query->where(array($db->quoteName('item_id') . ' = ' . $db->quote($id)));
            $query->where(array($db->quoteName('type') . ' = ' . $db->quote($type)));
            $db->setQuery($query);
            $likes = $db->loadObject();

            if(isset($likes)) {
                return $likes->count;
            } else {
                return '0';
            }
        }
    }

    public function getRecache() {
        $app = JFactory::getApplication();
        $id = $app->input->get('id', false);
        $this->deleteCache($id);
    }

    public function getLikeItem()
    {
        $app = JFactory::getApplication();
        $type = $app->input->get('type', false);
        $tableName = '#__likes';
        $id = $app->input->get('id', false);

        if ($id !== false && $id !== false) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName($tableName));
            $query->where(array($db->quoteName('item_id') . ' = ' . $db->quote($id)));
            $query->where(array($db->quoteName('type') . ' = ' .$db->quote($type)));
            $db->setQuery($query);
            $likes = $db->loadObject();

            // Save
            $fields = new stdClass();
            $fields->id = $likes->id;
            $fields->item_id = $id;
            $fields->type = $type;
            $fields->count = $likes->count+1;
            $db = JFactory::getDbo();
            try {
                $query = $db->getQuery(true);
                $result = JFactory::getDbo()->insertObject($tableName, $fields);
            } catch (Exception $e) {
                $result = JFactory::getDbo()->updateObject($tableName, $fields, 'id');
            }

            $session = JFactory::getSession();
            $givenLikeArray = $session->get("given_likes_$type", array());
            array_push($givenLikeArray,$id);
            $givenLikeArray = array_unique($givenLikeArray);
            $session->set("given_likes_$type",$givenLikeArray);

            return json_encode($fields);

        }


    }

    private function deleteCache($id)
    {
        $jotcache = new MainModelMain();
        $jotrecache = new MainModelRecache();
        $db = JFactory::getDbo();
        $tableName = '#__jotcache';
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($tableName));
        $db->setQuery($query);
        $items = $db->loadObjectList();

        foreach ($items as $key => $item) {
            $com = $item->com;
            $view = $item->view;
            $comView = "$com.$view";
            if($comView == 'com_content.category') {
                $idsToDelete[] = $item->fname;
            }
            if($comView == 'com_content.article' && $item->id == $id) {
                $idsToDelete[] = $item->fname;
            }
        }
        $app = JFactory::getApplication();
        $input = $app->input;

        if (count($idsToDelete) > 0) {
            $input->set('cid', $idsToDelete);
            $jotrecache->flagRecache($idsToDelete);
            $input->set('scope', 'chck');
            $input->set('mark', '1');
            $input->set('jotcacheplugin', 'recache');

        }

        $jotrecache->runRecache();
        $this->getResponse();
    }



    public function getResponse(): string
    {
        return $this->response;
    }

}
