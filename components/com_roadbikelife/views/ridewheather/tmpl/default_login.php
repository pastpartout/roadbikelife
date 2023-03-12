<?php
defined('_JEXEC') or die;


$doc = JFactory::getDocument();
$userr= JFactory::getUser();

?>
<?php if ($_GET['loggedout'] == '1'): ?>
    <div class="container">
        <div id="system-message-container">
            <div id="system-message">
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert">×</a>
                    <h4 class="alert-heading">success</h4>
                    <div>
                        <div class="alert-message">Abmelden erfolgreich</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<div class="rideWheather rideWheatherLogin">
    <img class="bgImage" src="media/com_roadbikelife/img/bg_rw.jpg">

    <?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header.php' ?>
    <section class="colWrapper">
        <div class="container">

            <div class="introWrapper">
                <h1 class="h3">
                    <?php //require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header_logo.php' ?>
                    <div class="pt-4">
                        Umfangreiche Wettervorhersagen für deine Touren
                    </div>
                </h1>
                <p class="col-md-8 mx-auto">
                    Lade eine GPX-Datei oder eine Strava-Route hoch und erfahre, ob du Gegewind oder Regen haben wirst und
                    welche Kleidung du eventuell tragen solltest.
                </p>

            </div>

            <div class="content">
                <form method="post" class="text-center"  >
                <fieldset class="input ">
                    <div class="shadow  rounded bg-white mb-4">
                        <div class="rounded  overflow-hidden">
                            <div class="input-group input-group-lg rounded-top">
                                    <span class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0">
                                            <i class="fa fa-at large fa-fw"></i>
                                        </span>
                                    </span>
                                <input id="username" type="text" required class="form-control rounded-0 input-lg"
                                       name="username" value="" placeholder="E-Mail-Adresse">
                            </div>
                            <div class="input-group input-group-lg rounded-bottom ">
                                    <span class="input-group-prepend rounded-0">
                                        <span class="input-group-text border-top-0   rounded-0">
                                            <i class="fa fa-lock large fa-fw"></i>
                                        </span>
                                    </span>
                                <input id="password" type="password" required
                                       class="form-control border-top-0 rounded-0 input-lg"
                                       name="password" value="" placeholder="Passwort">
                                <input type="hidden" class="form-control border-top-0 rounded-0 input-lg"
                                       name="task" value="login.verifyLogindata">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="continue" value="<?php echo $_GET['continue'] ?>">
                    <?php echo JHtml::_('form.token'); ?>

                    <div class="text-center form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow rounded">
                            <i class="fa fa-sign-in"></i>
                            Login
                        </button>
                    </div>
                    <div class="text-center pt-3 mb-3">
                        <a class=" text-decoration-none d-block text-white"
                           href="<?= JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=register') ?>">
                            <i class="fa fa-user-plus icon-margin-right"></i>
                            Für Testphase Registrieren
                        </a>
                    </div>
                    <div class="text-center pt-3 mb-3">
                        <a class=" text-decoration-none d-block text-white"
                           href="<?= JRoute::_('index.php?option=com_users&view=reset') ?>">
                            <i class="fa fa-refresh icon-margin-right"></i>
                            Passwort zurücksetzen
                        </a>
                    </div>


                </fieldset>
                </form>

            </div>


        </div>


    </section>
</div>



