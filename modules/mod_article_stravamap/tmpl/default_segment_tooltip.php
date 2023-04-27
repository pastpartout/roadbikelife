<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var stdClass $segmentEffort */
$segmentEffort->average_speed = $segmentEffort->distance / $segmentEffort->moving_time;

?>
<div class="segment-tooltip">
    <div class="icon text-warning">
		<? if ($segmentEffort->achievements[0]->rank === 1): ?><i class="fas fa-crown icon-margin-right"></i><? else: ?>
            <i class="fas fa-trophy icon-margin-right"></i><? endif; ?><?= $segmentEffort->achievements[0]->rank . '. Platz' ?>
    </div>
    <p class="title"><?= $segmentEffort->name ?></p>
    <table class="w-100 values">
        <tr>
            <td>
                <i class="fas fa-fw fa-arrows-h icon-margin-right "></i>Distanz:
            </td>
            <td>
				<?= RoadbikelifeHelper::formatRenderedValue($segmentEffort->distance, 'distance_m', true, true) ?>
            </td>
        </tr>
        <tr >
            <td>
				<? if ($segmentEffort->segment->average_grade > 0): ?>
                    <i class="fas fa-fw fa-arrow-right icon-margin-right" style="transform: rotate(-45deg)"></i>Steigung:
				<? else: ?>
                    <i class="fas fa-fw fa-arrow-right icon-margin-right" style="transform: rotate(45deg)"></i>Gefälle:
				<? endif; ?>
            </td>
            <td>
				<?= RoadbikelifeHelper::formatRenderedValue($segmentEffort->segment->average_grade, 'grade_smooth', true, true) ?>
            </td>
        </tr>
        <tr>
            <td>
                <i class="fas fa-fw fa-clock icon-margin-right "></i>Zeit:
            </td>
            <td>
			    <?= RoadbikelifeHelper::formatRenderedValue($segmentEffort->moving_time, 'moving_time_s', true, true) ?>
            </td>
        </tr>
        <tr>
            <td>
                <i class="fas fa-fw fa-tachometer icon-margin-right "></i>Geschw.:
            </td>
            <td>
				<?= RoadbikelifeHelper::formatRenderedValue($segmentEffort->average_speed, 'average_speed', true, true) ?>
            </td>
        </tr>

		<? if ($segmentEffort->device_watts === true): ?>
            <tr>
                <td>
                    <i class="fas fa-fw fa-bolt icon-margin-right"></i>Leistung:
                </td>
                <td>
                    <div>
						<?= RoadbikelifeHelper::formatRenderedValue($segmentEffort->average_watts, 'watts', true, true) ?>
                    </div>
                    <div>
	                    <?= str_replace('.',',',
	                            round($segmentEffort->average_watts/RoadbikelifeHelper::getParam('roadbikelife_rider_weight'),2),
                            ) ?> w/kg
                    </div>
                </td>
            </tr>
		<? endif; ?>

		<? if ($segmentEffort->average_heartrate > 0): ?>
            <tr>
                <td>
                    <i class="fas fa-fw fa-heartbeat icon-margin-right"></i>HF:
                </td>
                <td>
					<?= RoadbikelifeHelper::formatRenderedValue($segmentEffort->average_heartrate, 'average_heartrate', true, true) ?>
                </td>
            </tr>
		<? endif; ?>
    </table>

</div>