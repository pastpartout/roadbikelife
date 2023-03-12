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

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelRoadbikelife extends JModelList
{
    public $memberAccess;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(

			);
		}

		parent::__construct($config);


	}



    public function checkUserGroupAllowed($user) {
        $userGroups = $user->groups;

        $allowedUserGoupIds = array('8','10');
        foreach($userGroups as $userGroup){
            if(in_array($userGroup,$allowedUserGoupIds)) {
                return true;
            }
        }
    }


}
