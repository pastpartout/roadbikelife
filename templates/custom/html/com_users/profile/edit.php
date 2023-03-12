<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
//JHtml::_('behavior.formvalidator');
//JHtml::_('formbehavior.chosen', 'select');

// Load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript('templates/custom/js/jquery.min.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/i18next/8.1.0/i18next.min.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/jquery-i18next/1.2.0/jquery-i18next.min.js');
$doc->addScript('templates/custom/js/pwstrength-bootstrap.min.js');
$doc->addScript('templates/custom/js/initPwStrength.js');
$doc->addScript('templates/custom/js/jquery.validate.min.js');
$fieldsets = $this->form->getFieldsets();
?>
<script>
    function controlItem(el) {
        var $item = el.closest('.card');
        if ($item.find('input[name*="state"]:checked').val() == '1') {
            $item.addClass('opened');
        } else {
            $item.removeClass('opened');
        }
    }

    $(document).ready(function () {

        $('input[name*="state"]').on('change', function (e) {
            controlItem($(this));

        });


        $('.bikes-wrapper .card input[name*="state"]').each(function (key, item) {
            $item = $(item);
            controlItem($item);
        });

    });
</script>

<div class="page-header">
    <div class="logo text-center">
        <img src="<?php echo JURI::base() ?>/templates/custom/img/logo_tgrc.svg?v1" class="img-responsive"
             alt="Logo des Team Grizzly Cycling Club (TGRC)">
    </div>
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1 class="mb-4">
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    <?php endif; ?>
</div>

<div class="profile-edit<?php echo $this->pageclass_sfx; ?>">
    <script type="text/javascript">
        Joomla.twoFactorMethodChange = function (e) {
            var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

            jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function (i, el) {
                if (el.id != selectedPane) {
                    jQuery('#' + el.id).hide(0);
                } else {
                    jQuery('#' + el.id).show(0);
                }
            });
        }


    </script>
    <form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>"
          method="post" class="form-validate well edit-form" enctype="multipart/form-data">
        <?php // Iterate through the form fieldsets and display each one. ?>
        <div class="text-right">
            <p>
                <small>
                    <span class="text-warning star">&#160;*</span> Pflichtfelder
                </small>
            </p>
        </div>

        <div class="accordion border rounded" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <a class="d-flex h4 heading-serif mb-0" data-toggle="collapse" data-target="#collapseOne"
                       aria-expanded="true" aria-controls="collapseOne">
                        <span>
                        <?php echo JText::_($fieldsets['core']->label); ?>
                        </span>
                        <i class="fa fa-chevron-down ml-auto"></i>
                    </a>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                     data-parent="#accordionExample">
                    <div class="card-body">
                        <?php $fields = $this->form->getFieldset('core'); ?>
                        <?php foreach ($fields as $field) : ?>
                            <?php // If the field is hidden, just display the input. ?>
                            <?php if ($field->hidden) : ?>
                                <?php echo $field->input; ?>
                            <?php else : ?>
                                <div class="form-group" data-group="<?php echo $field->name; ?>">
                                    <div class="control-label">
                                        <?php echo $field->label; ?>
                                        <?php if (!$field->required && $field->type !== 'Spacer') : ?>
                                            <span class="optional">
                                            <?php echo JText::_('COM_USERS_OPTIONAL'); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="controls">
                                        <?php if ($field->fieldname === 'password1') : ?>
                                            <?php // Disables autocomplete ?>
                                            <input type="password" style="display:none">
                                        <?php endif; ?>
                                        <?php echo $field->input; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <a class="d-flex h4 heading-serif mb-0" data-toggle="collapse" data-target="#collapseTwo"
                       aria-expanded="false" aria-controls="collapseTwo">
                        <span>
                            <?php echo JText::_($fieldsets['fields-6']->label); ?>
                        </span>
                        <i class="fa fa-chevron-down ml-auto"></i>
                    </a>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">
                        <?php $fields = $this->form->getFieldset('fields-6'); ?>
                        <?php foreach ($fields as $field) : ?>
                            <?php // If the field is hidden, just display the input. ?>
                            <?php if ($field->hidden) : ?>
                                <?php echo $field->input; ?>
                            <?php else : ?>
                                <div class="form-group" data-group="<?php echo $field->name; ?>">
                                    <div class="control-label">
                                        <?php echo $field->label; ?>
                                        <?php if (!$field->required && $field->type !== 'Spacer') : ?>
                                            <span class="optional">
                                            <?php echo JText::_('COM_USERS_OPTIONAL'); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="controls">
                                        <?php if ($field->fieldname === 'password1') : ?>
                                            <?php // Disables autocomplete ?>
                                            <input type="password" style="display:none">
                                        <?php endif; ?>
                                        <?php echo $field->input; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <a class="d-flex h4 heading-serif mb-0" data-toggle="collapse" data-target="#collapseThree"
                       aria-expanded="false" aria-controls="collapseThree">
                        <span>
                            <?php echo JText::_($fieldsets['fields-3']->label); ?>
                        </span>
                        <i class="fa fa-chevron-down ml-auto"></i>
                    </a>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                    <div class="card-body">
                        <?php $fields = $this->form->getFieldset('fields-3'); ?>
                        <?php foreach ($fields as $field) : ?>
                            <?php // If the field is hidden, just display the input. ?>
                            <?php if ($field->hidden) : ?>
                                <?php echo $field->input; ?>
                            <?php else : ?>
                                <div class="form-group" data-group="<?php echo $field->name; ?>">
                                    <div class="control-label">
                                        <?php echo $field->label; ?>
                                        <?php if (!$field->required && $field->type !== 'Spacer') : ?>
                                            <span class="optional">
                                            <?php echo JText::_('COM_USERS_OPTIONAL'); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="controls">
                                        <?php if ($field->fieldname === 'password1') : ?>
                                            <?php // Disables autocomplete ?>
                                            <input type="password" style="display:none">
                                        <?php endif; ?>
                                        <?php echo $field->input; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingFour">
                    <a class="d-flex h4 heading-serif mb-0" data-toggle="collapse" data-target="#collapseFour"
                       aria-expanded="false" aria-controls="collapseFour">
                        <span>
                             Deine Bikes
                        </span>
                        <i class="fa fa-chevron-down ml-auto"></i>
                    </a>
                </div>

                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                    <div class="card-body">
                        <?php if (count($this->form->bikeForm->bikes)): ?>
                            <div class="bikes-wrapper">
                                <div class="row">
                                    <?php foreach ($this->form->bikeForm->bikes as $bike) : ?>
                                        <div class="col-lg-4 mb-4">
                                            <div class="card border rounded shadow-sm">
                                                <div class="card-body">
                                                    <div class="row no-gutters d-flex align-items-center">
                                                        <div class="col-6">
                                                            <div class="h4 heading-serif mb-0">
                                                                <?php echo $bike->name; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <?php $field = $this->form->getField('state_' . $bike->id); ?>
                                                            <?php echo $field->input; ?>
                                                        </div>
                                                    </div>
                                                    <div class="options mt-3">
                                                        <?php $field = $this->form->getField('image_' . $bike->id); ?>
                                                        <?php echo $field->input; ?>
                                                        <?php $field = $this->form->getField('description_' . $bike->id); ?>
                                                        <div class="mt-3">
                                                            <?php echo $field->label; ?>
                                                            <?php echo $field->input; ?>
                                                        </div>
                                                        <?php $field = $this->form->getField('showdistance_' . $bike->id); ?>
                                                        <div class="mt-2">
                                                            <?php echo $field->label; ?>
                                                            <?php echo $field->input; ?>
                                                        </div>

                                                        <input type="hidden" name="bikes[]"
                                                               value="<?php echo $bike->id; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <input type="hidden" name="strava_id"
                                           value=" <?php echo $this->form->bikeForm->stravaId; ?>">

                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="control-group mt-4 submit-group">
            <div class="controls d-flex justify-content-center">
                <button type="submit" data-title="Das Passwort ist nicht sicher genug"
                        class="btn px-4 btn-primary validate d-block col-12 col-md-auto">
                    <i class="fa fa-check"></i> <?php echo JText::_('JSAVE'); ?>
                </button>
                <input type="hidden" name="option" value="com_users"/>
                <input type="hidden" name="task" value="profile.save"/>
            </div>
        </div>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
