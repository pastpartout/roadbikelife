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

                <div class="alert alert-info text-center mb-0">
                    Leider ist die Testphase beendet. Das Tool wird nur noch für einen begrenzten Zeitraum
                    funtionieren, da der Anbieter der Wetterdaten DarkSky Wheather den Dienst einstellen wird.
                </div>

            </div>


        </div>


    </section>
</div>



