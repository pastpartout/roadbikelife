<?php
defined('_JEXEC') or die;

/** @var TYPE_NAME $stravaFields */
JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');

?>
<?php if (isset($stravaStats->distance)) : ?>
    <div class="personalStravaStats personalStravaStats-year">

        <div class="text-center postInfos postInfos-strava">
            <div class="d-flex justify-content-around flex-wrap">
                <?php foreach ($stravaFields as $key => $field): ?>
                    <?php if ((int)$stravaStats->{$key} !== 0 && $field != ''): ?>
                            <div class="item">
                                <div class="itemInner">
                                    <i class="fal fa-fw text-gradient mb-2 fa-2x <?= $field['icon'] ?>"></i>
                                    <span class="h5 mb-0">
                                        <?= RoadbikelifeHelper::formatRenderedValue($stravaStats->{$key}, $key, true, true) ?>
                                    </span>
                                    <span class="small"><?= JTEXT::_(strtoupper($key)) ?></span>
                                </div>
                            </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
            <a href="http://www.strava.com/athletes/<?php echo RoadbikelifeHelper::getParam('strava_profileIs') ?>"
               target="_blank" class="strava-link">
                <img src="<?php echo JUri::base() ?>templates/custom/img/api_logo_pwrdBy_strava_stack_gray.svg"
                     alt="Powered by Strava" class="img-fluid">
                <div class="small">
                    View on Strava
                </div>
            </a>
        </div>
    </div>
<?php endif; ?>
