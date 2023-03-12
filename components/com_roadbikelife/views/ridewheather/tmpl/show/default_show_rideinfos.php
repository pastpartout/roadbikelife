<?php

defined('_JEXEC') or die;
?>
<div class="rideInfos">
    <div class="row">
        <div class="col-md-4">
            <div class="valueLabel">
                Streckenlänge
            </div>
            <?= RoadbikelifeHelper::formatRenderedValue(
                $item->distance * 1000, 'distance',
                true, true) ?>

        </div>
        <div class="col-md-4">
            <div class="valueLabel">
                Gesamtanstieg
            </div>
            <?= RoadbikelifeHelper::formatRenderedValue($item->total_elevation_gain, 'total_elevation_gain', true, true) ?>
        </div>
        <div class="col-md-4">


        </div>
        <div class="col-md-4">
            <div class="valueLabel">
                Startzeit:
            </div>
            <?php echo JHtml::_('date', $item->start_date, JText::_('DATE_FORMAT_LC5')); ?>
        </div>
        <div class="col-md-4">
            <div class="valueLabel">
                Fahrzeit bei <?= $item->speed ?> km/h
            </div>
            <?= $item->moving_time ?>
        </div>
        <div class="col-md-4">
            <div class="valueLabel">
                Ankunftszeit bei <?= $item->speed ?> km/h
            </div>
            <?php echo JHtml::_('date', $item->finish_time, JText::_('d.m.Y H:i')); ?>
        </div>
    </div>
</div>
