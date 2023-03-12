<?php

defined('_JEXEC') or die;

?>
<div class="mapWrapper">
    <div id="googlemap" class="googleMap"></div>
    <div id="wheather" class="wheatherWidget">
        <div class="wind">
            <div class="windBearing">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div class="windInfo">
                <div class="progress">
                    <div class="progress-bar" style="height: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <div class="text">
                            <i class="fal fa-wind large"></i><br>
                            Wind<br>
                            <span class="windSpeed"></span><br>km/h
                        </div>
                    </div>
                </div>
                <div class="p-1">
                    <i id="wheatherIcon"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="btn btn-default" id="showWindBearing" data-toggle-mapicons="wind"><i class="fa fa-wind"></i>
        Wind
    </div>
    <div class="btn btn-default" id="showTemperature" data-toggle-mapicons="temperature">
        <i class="fa fa-wind"></i> Temperatur
    </div>
</div>
