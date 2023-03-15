<?php
$fields = [
    'distance' => ['icon' => 'fa-arrows-h'],
    'average_speed' => ['icon' => 'fa-tachometer'],
    'total_elevation_gain' => ['icon' => 'fa-mountains'],
    'moving_time' => ['icon' => 'fa-clock'],
    'kudos_count' => ['icon' => 'fa-thumbs-up'],
    'achievement_count' => ['icon' => 'fa-trophy'],
    'athlete_count' => ['icon' => 'fa-users'],
    'weighted_average_watts' => ['icon' => 'fa-bolt'],
    'average_temp' => ['icon' => 'fa-thermometer-three-quarters'],
];


?>

<?php if (isset($stravaActivitiesPepared) && count($stravaActivitiesPepared) > 0): ?>
<div class="content-strava">
    <a class="anchor" id="content-strava"></a>
    <?php if ($module->showtitle): ?>
        <h3 class="rblHeadline">
            <span>
                  <?= $module->title; ?>
            </span>
        </h3>
    <?php endif; ?>
    <div class="postInfos postInfos-strava mb-3 py-3">
	    <?php foreach ($stravaActivitiesPepared as $stravaActivityKey => $stravaActivity): ?>
            <div class="flex-wrap  postInfos-strava-inner
            <?php if($stravaActivityKey == 0): ?>d-flex<?php else: ?> d-none<?php endif ?>"
                 data-strava-id="<?= $stravaActivity->id ?>">
                <?php $i = 0; ?>
                <?php foreach ($fields as $key => $field): ?>
                    <?php $i++; ?>
                    <?php if ((int)$stravaActivity->$key !== 0 && $stravaActivity->$key !== false): ?>
                        <?php if ($key == 'average_temp' && $stravaActivity->average_temp !== false): ?>
                            <div class="item">
                                <div class="itemInner">
                                    <?php if ((int)$stravaActivity->average_temp > 20): ?>
                                        <i class="fal fa-fw  fa-2x  fa-thermometer-full"></i>
                                    <?php elseif ((int)$stravaActivity->average_temp < 10): ?>
                                        <i class="fal fa-fw  fa-2x  fa-thermometer-quarter"></i>
                                    <?php else: ?>
                                        <i class="fal fa-fw  fa-2x  fa-thermometer-half"></i>
                                    <?php endif ?>
                                    <div>
                                        <div class="h5 d-block mb-0">
                                            <?= RoadbikelifeHelper::formatRenderedValue($stravaActivity->$key, $key, true, true) ?>
                                        </div>
                                        <div class="small text-white-50"><?= JTEXT::_(strtoupper($key)) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($key == 'athlete_count'): ?>
                            <?php if ($stravaActivity->athlete_count > 1): ?>
                                <div class="item">
                                    <div class="itemInner">
                                        <i class="fal fa-fw  fa-2x <?= $field['icon'] ?>"></i>
                                        <div>
                                            <div class="h5 d-block mb-0">
                                                <?= RoadbikelifeHelper::formatRenderedValue($stravaActivity->$key, $key, true, true) ?>
                                            </div>
                                            <div class="small text-white-50"><?= JTEXT::_(strtoupper($key)) ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php else: ?>
                            <div class="item">
                                <div class="itemInner">
                                    <i class="fal fa-fw  fa-2x <?= $field['icon'] ?>"></i>
                                    <div>
                                        <div class="h5 d-block mb-0">
                                            <?= RoadbikelifeHelper::formatRenderedValue($stravaActivity->$key, $key, true, true) ?>
                                        </div>
                                        <div class="small text-white-50"><?= JTEXT::_(strtoupper($key)) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
	    <?php endforeach ?>

        <a href="http://www.strava.com/activities/<?= $stravaActivity->id ?>"
           target="_blank" class="strava-link" data-toggle="tooltip" data-title="View on Strava">
            <img src="<?php echo JUri::base() ?>templates/custom/img/api_logo_pwrdBy_strava_stack_gray.svg"
                 alt="Powered by Strava" class="img-fluid">
        </a>
    </div>
<?php endif; ?>
