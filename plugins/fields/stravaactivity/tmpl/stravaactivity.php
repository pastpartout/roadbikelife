<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Stravaactivity
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;
Text::script('JGLOBAL_SELECT_NO_RESULTS_MATCH');
Text::script('JGLOBAL_SELECT_PRESS_TO_SELECT');

Factory::getApplication()->getDocument()->getWebAssetManager()
	->usePreset('choicesjs')
	->useScript('webcomponent.field-fancy-select');

$fieldValue = $field->value;

if ($fieldValue == '')
{
	return;
}

$fieldValue = (array) $fieldValue;
$texts      = array();
$options    = $this->getOptionsFromField($field);

foreach ($options as $value => $name)
{
	if (in_array((string) $value, $fieldValue))
	{
		$texts[] = JText::_($name);
	}
}


echo htmlentities(implode(', ', $texts));
