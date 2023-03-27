<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Stravaactivity
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::import('components.com_fields.libraries.fieldslistplugin', JPATH_ADMINISTRATOR);

/**
 * Fields stravaactivity Plugin
 *
 * @since  3.7.0
 */
class PlgFieldsStravaactivity extends FieldsListPlugin
{
    public function onCustomFieldsPrepareField($context, $item, $field)
    {

        return parent::onCustomFieldsPrepareField($context, $item, $field);
    }

    public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
    {
        $fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);

        if (!$fieldNode)
        {
            return $fieldNode;
        }

        $fieldNode->setAttribute('value_field', 'text');
        $fieldNode->setAttribute('key_field', 'value');

        return $fieldNode;
    }
}
