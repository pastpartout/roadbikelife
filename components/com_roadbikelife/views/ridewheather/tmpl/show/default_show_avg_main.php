<?php

defined('_JEXEC') or die;


?>
<div class="row no-gutters wheatherAvgMainWrapper">
    <div class="col-2 col-md-4 text-center d-flex justify-content-center">
        <i class="fas fa-4x <?= $item->icon_avg; ?> wheatherAvgMainIcon"></i>
    </div>
    <div class="col-5 col-md-4 pl-3">
        <div class="wheatherAvgMain ">
            <div class="small valueLabel">
                <i class="fa fa-thermometer-empty mr-1"></i>Temp. Ø
            </div>
            <div class="large">
                <?= $item->temperature_avg; ?>
                <?= $this->averageItems['temperature']['unit']; ?>
            </div>
        </div>
    </div>
    <div class="col-5 col-md-4 pl-3">
        <div class="wheatherAvgMain ">
            <div class="small valueLabel">
                <i class="fa fa-humidity mr-1"></i>Regenwahrsch. Ø
            </div>
            <div class="large">
                <?= round($item->precipprobability_avg * 100, 2); ?>
                <?= $this->averageItems['precipProbability']['unit']; ?>
            </div>
        </div>
    </div>
</div>

