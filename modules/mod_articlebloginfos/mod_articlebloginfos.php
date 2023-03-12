<?php
/**
 * @copyright	Copyright © 2019 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$app = Factory::getApplication();
$doc = $app->getDocument();
$id = $app->input->getCmd('id', 0);
$fieldComponent     = $app->bootComponent('com_fields');
$fieldModel = $fieldComponent->getMVCFactory()->createModel('Field','Administrator');

if ($id !== 0) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__fields'));
    $query->where(array($db->quoteName('group_id') . ' = 1'));
    $db->setQuery($query);
    $fields = $db->loadObjectList('name');

    $fieldsAvailable = false;

    foreach ($fields as $field) {
        $field->value = $fieldModel->getFieldValue($field->id, $id);
        if($field->value != '') {
            $fieldsAvailable = true;
        }
    }
}

require JModuleHelper::getLayoutPath('mod_articlebloginfos', $params->get('layout', 'default'));