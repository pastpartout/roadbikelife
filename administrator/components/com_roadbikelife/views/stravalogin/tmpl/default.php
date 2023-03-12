<?php
defined('_JEXEC') or die;


?>
<div id="com_roadbikelife" class="index">
    <div class="container">
        <div class="page-header">
            <img src="<?php echo JURI::root() ?>/templates/custom/img/logo_black.svg" class="img-fluid rbllogo">
            <h1 class="text-center">roadbikelife Strava Login</h1>
        </div>
        <div class="content">
            <div class="text-center">
                <p class="h3">
                    <i class="fa fa-chevron-circle-down fa-2x"></i>
                </p>
                <a class="btn-strava-login mb-4"
                   href="https://www.strava.com/oauth/authorize?client_id=<?= RoadbikelifeHelper::getParam('strava_clientId') ?>&response_type=code&redirect_uri=<?php echo urlencode(JURI::base() . 'index.php?option=com_roadbikelife&task=stravalogin.success') ?>&approval_prompt=auto&scope=read_all,activity:read_all,profile:read_all">
                    <img class="img-fluid"
                         src="<?php echo JUri::root() . 'templates/custom/img/btn_strava_connectwith_light.svg' ?>"
                         alt="mit Strava anmelden"/>
                </a>

            </div>
        </div>
    </div>
</div>

