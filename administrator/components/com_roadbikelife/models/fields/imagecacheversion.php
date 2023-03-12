<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */


defined('_JEXEC') or die('Restricted access');

class JFormFieldImagecacheversion extends \Joomla\CMS\Form\FormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'image_cache_version';


	protected function getInput()
	{
		// Initialize variables.
		$html[] = "<input type='hidden' name='$this->name' value='$this->value' /> ";
		$html[] = "<span class='badge badge-efault'>Version: $this->value</span> ";
		$html[] = '<a href="'.JURI::base()."index.php?option=com_roadbikelife&task=imagecacheversion.update\" class=\"btn btn-default\">Update</a>";

		return implode($html);
	}
}
