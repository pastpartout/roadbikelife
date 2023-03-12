<?php
/**
 * @copyright	Copyright © 2019 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$articleAboutMe = JTable::getInstance("content");
$articleAboutMe->load('5');

require JModuleHelper::getLayoutPath('mod_about_me', $params->get('layout', 'default'));