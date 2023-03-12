<?php

use Joomla\CMS\Factory;
use Joomla\Event\Event;

defined('_JEXEC') or die;


$doc = JFactory::getDocument();
$headerTitle = 'Registrierung';
//php code
JPluginHelper::importPlugin('captcha');
$app = Factory::getApplication();
$dispatcher = $app->getDispatcher();
$event = new Event('dynamic_recaptcha_1',[]);
$dispatcher->dispatch('onInit',$event);

$doc->addScript('templates/custom/js/jquery.validate.min.js');
$doc->addScript('media/media/js/localization/messages_de.js');
$doc->addScript('media/media/js/localization/methods_de.js');

$doc->addScriptDeclaration('
$(function () {
    $(\'.form-validate\').validate({
            rules:{
                loc: {
                    // minlength: 3,
                    // required: true,
                    loc: true
                }
            },
            
            errorClass: \'error text-danger\',
            errorElement: "div",
            errorPlacement: function(error, element) {
                error.insertAfter(element.closest(\'div\')).addClass(\'mt-2 small\');
            },
            success: function(element) {
                element.addClass("form-control-success");
            },
            // onkeyup: function(element) { $(element).valid(); },
            onfocusout: function(element) {
                $(element).valid();
    
            }
        });
        $( \'[type="password"]\' ).rules( "add", {
            minlength: 6,
            messages: {
                minlength: jQuery.validator.format("Bitte wähle eine mindesten {0} Zeichen langes Passwort")
            }
        });
    });
');

?>


<div class="rideWheather rideWheatherRegister">
    <img class="bgImage" src="media/com_roadbikelife/img/bg_rw.jpg">

    <?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header.php' ?>
    <section class="colWrapper">
        <div class="container">

            <div class="introWrapper">
                <h1 class="h3">
                    <?php //require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header_logo.php' ?>
                    <div class="pt-4">
                        Registriere dich für die Testphase von RideWheather
                    </div>
                </h1>
            </div>

            <div class="card text-body">

                <form method="post" novalidate class="card-body form-validate">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <? $field = $this->form->getField('prename'); ?>
                                <?= $field->label; ?>
                                <?= $field->input; ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $field = $this->form->getField('lastname'); ?>
                                <?= $field->label; ?>
                                <?= $field->input; ?>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <? $field = $this->form->getField('email'); ?>
                                <?= $field->label; ?>
                                <?= $field->input; ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php
                                $field = $this->form->getField('password'); ?>
                                <?= $field->label; ?>
                                <?= $field->input; ?>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 d-flex justify-content-center">
                        <div class="form-group">
                            <?php
                            $field = $this->form->getField('captcha'); ?>
                            <?= $field->input; ?>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex w-100 justify-content-center text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-angle-right icon-margin-right"></i>Registrieren
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="task" value="register.save">
                    <?php echo JHtml::_('form.token'); ?>
                </form>

            </div>


        </div>


    </section>
</div>



