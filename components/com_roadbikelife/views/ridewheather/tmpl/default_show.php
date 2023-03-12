<?php

defined('_JEXEC') or die;

$item = $this->item;
?>
<div class="rideWheather">
    <?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header.php' ?>

    <section class="row no-gutters colWrapper">
        <div class="col-md-8 mapCol">
            <div class="sectionButtonCol d-flex d-md-none p-2">
                <button type="button" class="btn w-100 btn-light btn-sm"
                        data-toggle="modal" data-target="#changeDateModal">
                    <i class="fal fa-edit icon-margin-right"></i>Zeit/Tempo ändern
                </button>
            </div>
            <div class="d-block d-md-none mobileAvg">
                <?php require __DIR__.'/show/default_show_avg_main.php' ?>
            </div>
            <?php require __DIR__.'/show/default_show_map.php' ?>
            <?php require __DIR__.'/show/default_show_map_graphs.php' ?>
        </div>

        <div class="col-md-4 colInfo">
            <div class="scroller">
                <div class="colHeading">
                    <div class="row">
                        <div class="col-md-6 sectionHeaderCol">
                            <div class="sectionHeader">Zusammenfassung</div>
                        </div>
                        <div class="col-md-6 sectionButtonCol d-none d-md-flex">
                            <button type="button" class="btn btn-light btn-sm"
                                    data-toggle="modal" data-target="#changeDateModal">
                                <i class="fal fa-edit icon-margin-right"></i>Zeit/Tempo ändern
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-none d-md-block text-white ">
                    <?php require __DIR__.'/show/default_show_avg_main.php' ?>
                </div>

                <?php require __DIR__.'/show/default_show_averages.php' ?>
                <?php require __DIR__.'/show/default_show_rideinfos.php' ?>
                <div class="temperatureRainGraphWrapper">
                    <p class="font-weight-bold pl-2">Regenwahrscheinlichkeit</p>
                    <div id="temperature-rain-graph" class="temperatureRainGraph">
                    </div>
                </div>
                <?php require __DIR__.'/show/default_show_clothingrecs.php' ?>
                <?php require __DIR__.'/show/default_show_startfinish.php' ?>
            </div>
        </div>

    </section>
</div>
<?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_edit.php' ?>

