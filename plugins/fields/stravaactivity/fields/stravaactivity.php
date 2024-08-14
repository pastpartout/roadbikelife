<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Stravaactivity
 *
 * @copyright   Copyright (C) 2017 NAME. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
JLoader::register('RoadbikelifeHelper', JPATH_ROOT . '/administrator/components/com_roadbikelife/helpers/roadbikelife.php');
JLoader::register('RoadbikelifeModelApiupdatestrava', JPATH_ROOT . '/components/com_roadbikelife/models/apiupdatestrava.php');

class JFormFieldStravaactivity extends JFormFieldList
{
    public $type = 'Stravaactivity';
    /**
     * @var RoadbikelifeModelApiupdatestrava
     * @since version
     */
    private RoadbikelifeModelApiupdatestrava $strava_model;

    protected function getOptions()
    {
        $this->strava_model = new RoadbikelifeModelApiupdatestrava();
        $limit = $this->getAttribute('api_list_limit', '10');
        $options = [];

        $this->strava_model->setStravaApi();
        $this->strava_model->athenticate();

        $activitities = $this->strava_model->stravaApi->get('athlete/activities', ['per_page' => $limit, [], 'before' => strtotime('now')]);;

        foreach ($activitities as $activitity) {
            $options[] = [
                'value' => $activitity->id,
                'text' => $activitity->name,
            ];
        }

        $loadedActivitiesIds = \Joomla\Utilities\ArrayHelper::getColumn($activitities ?? [], 'id');

        if ($this->multiple) {

            foreach ($this->value as $value) {
                if (!in_array($value, $loadedActivitiesIds)) {
                    $activitity = $this->strava_model->stravaApi->get('activities/' . $value);
                    $option = [
                        'value' => $activitity->id,
                        'text' => $activitity->name,
                    ];
                    array_push($options,$option );
                }
            }
        } else if (!in_array($this->value, $loadedActivitiesIds)) {
            $activitity = $this->strava_model->stravaApi->get('activities/' . $this->value);
            $option = [
                'value' => $activitity->id,
                'text' => $activitity->name,
            ];
            array_push($options,$option);
        }

        return $options;

    }


    protected function getInput()
    {
        $html = [];
        $attr = '';

        // Initialize some field attributes.
        $attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
        $attr .= $this->multiple ? ' multiple' : '';
        $attr .= $this->required ? ' required aria-required="true"' : '';
        $attr .= $this->autofocus ? ' autofocus' : '';

        // To avoid user's confusion, readonly="true" should imply disabled="true".
        if ((string)$this->readonly == '1' || (string)$this->readonly == 'true' || (string)$this->disabled == '1' || (string)$this->disabled == 'true') {
            $attr .= ' disabled="disabled"';
        }

        // Initialize JavaScript field attributes.
        $attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

        // Get the field options.
        $options = (array)$this->getOptions();

        // Create a read-only list (no name) with hidden input(s) to store the value(s).
        if ((string)$this->readonly == '1' || (string)$this->readonly == 'true') {
            $html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);

            // E.g. form field type tag sends $this->value as array
            if ($this->multiple && is_array($this->value)) {
                if (!count($this->value)) {
                    $this->value[] = '';
                }

                foreach ($this->value as $value) {
                    $html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"/>';
                }
            } else {
                $html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>';
            }
        } else // Create a regular list passing the arguments in an array.
        {


            array_unshift($options, [
                'value' => ' ',
                'text' => 'keine',
            ]);

            $listoptions = [];
            $listoptions['option.key'] = 'value';
            $listoptions['option.text'] = 'text';
            $listoptions['list.select'] = $this->value;
            $listoptions['id'] = $this->id;
            $listoptions['list.translate'] = false;
            $listoptions['option.attr'] = 'optionattr';
            $listoptions['list.attr'] = trim($attr);


            $html[] = JHtml::_("select.genericlist", $options, $this->name, $listoptions);

        }

        return "<joomla-field-fancy-select>" . implode($html) . "</joomla-field-fancy-select>";

    }

}
