<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_BASE . '/components/com_roadbikelife/models/resizeimage.php';

/** @var plgcontentContentMedia $googleMapHtml */
/** @var array $stravaActivityFieldValues */
/** @var array $flotGraphs */
/** @var object $stravaActivity */
/** @var object $modArticleStravaMap */
/** @var array $stravaActivitiesTotals */
$mapIdleImage = 'templates/custom/img/bg_map.jpg'

?>
<div class="stravaRideDetails">
    <a class="anchor" id="content-gpx"></a>
    <?php if (isset($stravaActivities)): ?>
        <div class="strava-activity-changer">
            <div class="d-flex align-items-center text-center">
                <div
                        data-toggle="collapse" data-target="#collapse-tours"
                        aria-expanded="true"
                        aria-controls="collapse-tours"
                        class="h5 d-block dropdown-toggle text-center text-white pt-3 pt-lg-0"
                        type="button"
                        id="dropdownToursButton" data-toggle="dropdown" data-toggle="collapse">
                    <?= count($stravaActivities) ?> Aktivitäten
                </div>
            </div>
            <div class="activities show" id="collapse-tours">
                <?php foreach ($stravaActivities as $key => $stravaActivityCollapseItem) : ?>
                    <a data-href="<?= JURI::base() . 'component/roadbikelife/mapdata/' . $stravaActivityCollapseItem->id ?>"
                       class="changeMap <? if ($key == 0): ?>disabled<? endif ?>"
                       data-strava-id="<?= $stravaActivityCollapseItem->id ?>">
                        <i class="fal fa-check"></i>
                        <span class="title">
                            <?= $stravaActivityCollapseItem->name ?>
                            <span class="date">
					            <?php echo JHtml::_('date', $stravaActivityCollapseItem->start_date, JText::_('DATE_FORMAT_LC5')); ?>
                            </span>

                        </span>
                        <span class="badges">
                            <span class="badge bage-sm">
                                <i class="fal fa-arrows-h"></i>
                               <?= RoadbikelifeHelper::formatRenderedValue($stravaActivityCollapseItem->distance, 'distance', true, true) ?>
                            </span>
                            <span class="badge bage-sm">
                                <i class="fal fa-mountains"></i>
                               <?= RoadbikelifeHelper::formatRenderedValue($stravaActivityCollapseItem->total_elevation_gain, 'total_elevation_gain', true, true) ?>
                            </span>
                            <span class="badge bage-sm">
                                <i class="fal fa-tachometer-alt-average"></i>
                               <?= RoadbikelifeHelper::formatRenderedValue($stravaActivityCollapseItem->average_speed, 'average_speed', true, true) ?>
                            </span>
                            <span class="badge bage-sm d-inline-block d-md-none">
                                <i class="fal fa-bolt"></i>
                               <?= RoadbikelifeHelper::formatRenderedValue($stravaActivityCollapseItem->average_watts, 'average_watts', true, true) ?>
                            </span>
                        </span>
                    </a>
                <?php endforeach ?>
                <div class="summary">
                    <span class="title font-weight-bold">
                        Gesamt
                    </span>
                    <span class="badges">
                            <?php if ((int)$stravaActivitiesTotals['average_watts'] > 0): ?>
                                <span class="badge bage-sm badge-watts">
                                <i class="fal fa-bolt"></i>
                                <?= $stravaActivitiesTotals['average_watts'] ?>
                            </span>
                            <?php endif ?>
                            <span class="badge bage-sm badge-calories">
                                <i class="fal fa-pizza-slice"></i>
                                <?= $stravaActivitiesTotals['calories'] ?>
                            </span>
                            <span class="badge bage-sm badge-moving-time">
                                <i class="fal fa-clock"></i>
                                <?= $stravaActivitiesTotals['moving_time'] ?>
                            </span>
                            <span class="badge bage-sm badge-distance">
                                <i class="fal fa-arrows-h"></i>
                               <?= $stravaActivitiesTotals['distance'] ?>
                            </span>
                            <span class="badge bage-sm badge-total-elevation-gain">
                                <i class="fal fa-mountains"></i>
                               <?= $stravaActivitiesTotals['total_elevation_gain'] ?>
                            </span>
                            <span class="badge bage-sm badge-average-speed">
                                <i class="fal fa-tachometer-alt-average"></i>
                               <?= $stravaActivitiesTotals['average_speed'] ?>
                            </span>
                        </span>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div class="mapWrapper" id="map-wrapper">
        <div id="googlemap" class="googleMap">
            <div class="gdpr-notice">
                <picture>
                    <source
                            type="image/webp"
                            srcset="<?= RoadbikelifeModelResizeimage::resizeImage($mapIdleImage, 2000, null, 1) ?>">
                    <img <?php if (isset($imagePosition)): ?>
                        style="object-position: center <?= $imagePosition ?>"<?php endif ?>
                            itemprop="image"
                            src="<?= RoadbikelifeModelResizeimage::resizeImage($mapIdleImage, 2000, null) ?>"
                            alt=""
                            class="bg-image img-fluid">
                </picture>
                <div class="content">
                    <p class="font-weight-bold">Google Maps Datenschutzhinweis</p>
                    <p>
                        Diese Website verwendet Google Maps. Mit Klick auf diesen Button werden Daten wie z.B. deine
                        IP-Adresse
                        an Google übermittelt. Weitere Informationen können der
                        <a href="<?= JUri::base() . 'datenschutzerklaerung?tmpl=component' ?>" class="prevent-event"
                           data-fancybox data-type="iframe" class="data-privacy-iframe">Datenschutzerklärung</a>
                        entnommen werden.
                    </p>
                    <a class="btn btn-sm btn-outline-light my-3">Zustimmen</a>
                </div>

            </div>
        </div>
        <?php if (isset($stravaActivity->wheather_json)): ?>
            <div id="wheather" class="wheatherWidget">
                <div class="wind">
                    <div class="windBearing">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="windInfo">
                        <div class="progress">
                            <div class="progress-bar" style="height: 0%" role="progressbar" aria-valuenow="0"
                                 aria-valuemin="0" aria-valuemax="100">
                                <div class="text">
                                    <i class="fal fa-wind large"></i><br>
                                    Wind<br>
                                    <span class="windSpeed"></span><br>km/h
                                </div>
                            </div>
                        </div>
                        <div class="p-1">
                            <i id="wheatherIcon"></i>
                            <div class="temp"><span></span>°C</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="controls-ridewheather">
        <div class="row no-gutters">
            <div class="col-4">
                <div class="small d-flex align-items-center">
                    <div class="btn-group graphAnimationControls">
                        <a class="btn btn-lg play active">
                            <i class="fas fa-fw fa-play"></i>
                        </a>
                        <a class="btn btn-lg pause">
                            <i class="fas fa-fw fa-pause"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-8 text-right">
                <?php if ($stravaActivity->activity_json->id > 0): ?>
                    <a href="<?= JURI::base() . 'component/roadbikelife/gpxdownload/' . $stravaActivity->activity_json->id ?>"
                       class="font-weight-bold py-1 px-2 small" id="btn-gpxdownload">
                        <i class="fas fa-route mr-1"></i>
                        GPX Download
                    </a>
                <?php endif ?>
            </div>
            <div class="col-8 text-right d-none">
                <a href="<?= JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=login') ?>"
                   target="_blank"
                   class="poweredByRideWheather">
                    <div class="xsmall">powered by</div>
                    <div class="badge">
                        RideWheather<i class="fa fa-sun ml-1"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>


    <div class="small detailNumbers pt-1 pt-md-0">
        <div class="row no-gutters">
            <?php foreach ($flotGraphs as $key => $flotGraph): ?>
                <div class="col-6 col-lg-3">
                    <div class="px-3 py-0 py-md-2 small">
                        <i class="fa <?= $flotGraph['icon'] ?> icon-margin-right fa-fw"></i>
                        <span class="<?= $key ?>">-</span>&nbsp;<?= $flotGraph['unit'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="">

        <div class="graphWrapper ">
            <div class="tab-content">
                <?php foreach ($flotGraphs as $key => $flotGraph): ?>
                    <div class="tab-pane fade <?php if ($key == 'altitude'): ?>show active<?php endif ?>"
                         id="nav-<?= $key ?>" role="tabpanel">
                        <div id="<?= $key ?>-graph" class="stravaGraph"></div>
                        <div class="marker"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="nav navGraphs" id="nav-tab" role="tablist">
            <?php foreach ($flotGraphs as $key => $flotGraph): ?>
                <a class="nav-item d-none <?php if ($key == 'altitude'): ?>active<?php endif ?>"
                   data-toggle="tab"
                   data-graph-name="#<?= $key ?>-graph"
                   href="#nav-<?= $key ?>"
                   role="tab" aria-controls="nav-<?= $key ?>"

                >
                    <i class="fal fa-fw <?= $flotGraph['icon'] ?> icon-margin-right fa-fw"></i><?= JText::_(strtoupper("COLLAPSIBLE_$key")); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?= $modArticleStravaMap->googleMapHtml ?>
</div>
