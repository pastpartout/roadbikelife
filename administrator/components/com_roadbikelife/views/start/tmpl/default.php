<?php
defined('_JEXEC') or die;


?>
<div id="com_roadbikelife" class="index">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="text-center">
                    <img src="<?php echo JURI::root() ?>/templates/custom/img/logo_black.svg" class="img-responsive rbllogo">
                </div>
            </div>

            <h1 class="text-center">roadbikelife Komponente</h1>
        </div>
        <div class="content">
            <div class="text-center">

                <div class="btn-wrapper" >
                    <a class="btn-strava-login mb-4 btn btn-primary btn-xl"
                       href="<?php echo JURI::base() . 'index.php?option=com_roadbikelife&view=stravalogin' ?>">
                        Strava Login
                    </a>
                    <a class="btn-strava-login mb-4 btn btn-primary btn-xl"
                       href="<?php echo JURI::base() . 'index.php?option=com_roadbikelife&view=createcontent' ?>">
                        Beitrag erstellen
                    </a>
                    <a class="btn-strava-login mb-4 btn btn-primary btn-xl"
                       href="<?php echo JURI::base() . 'index.php?option=com_roadbikelife&view=analytics' ?>">
                        Statistik
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>

