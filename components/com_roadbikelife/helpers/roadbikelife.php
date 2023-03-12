<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Class RoadbikelifeFrontendHelper
 *
 * @since  1.6
 */
class RoadbikelifeHelpersRoadbikelife
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_roadbikelife/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_roadbikelife/models/' . strtolower($name) . '.php';
			$model = BaseDatabaseModel::getInstance($name, 'RoadbikelifeModel');
		}

		return $model;
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function canUserEdit($item)
    {
        $permission = false;
        $user       = Factory::getUser();

        if ($user->authorise('core.edit', 'com_roadbikelife'))
        {
            $permission = true;
        }
        else
        {
            if (isset($item->created_by))
            {
                if ($user->authorise('core.edit.own', 'com_roadbikelife') && $item->created_by == $user->id)
                {
                    $permission = true;
                }
            }
            else
            {
                $permission = true;
            }
        }

        return $permission;
    }

    public static function deleteCache($comViewsToDelete)
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
            if(in_array($comView,$comViewsToDelete)) {
                $idsToDelete[] = $item->fname;
            }
        }
        $app = JFactory::getApplication();
        $input = $app->input;
        $input->set('cid', $idsToDelete);
        $input->set('scope', 'chck');
        $input->set('jotcacheplugin', 'crawler');

        if (count($idsToDelete) > 0) {
            $jotrecache->flagRecache($idsToDelete);
        }
        $jotrecache->logging = false;
        $jotrecache->runRecache();
        $jotrecache->controlRecache(0);
    }


}
