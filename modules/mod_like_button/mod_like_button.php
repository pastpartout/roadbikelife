<?php
/**
 * @copyright	Copyright © 2019 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();

JLoader::import('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');
$model = JModelLegacy::getInstance('Article', 'ContentModel');
//$model->getState();
$item = $model->getItem($module->content_id);


require JModuleHelper::getLayoutPath('mod_like_button', $params->get('layout', 'default'));
