<?php

defined('_JEXEC') or die;

$headerTitle = 'Deine Uploads';
$uploadLimits = $this->model->uploadLimits;

?>
<div class="rideWheather rideWheatherList">
    <?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header.php' ?>
    <section class="row no-gutters colWrapper">
        <div class="container">
            <div class="limitWrapper">
                <div class="row">
                    <div class="col-md-6">
                        <p class="font-weight-bold">Limit täglich</p>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $this->limitStatus->day->percent ?>%"
                                 aria-valuenow="<?= $this->limitStatus->day->percent ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="p-2 text-body"><?= $this->limitStatus->day->count ?> von <?= $uploadLimits['day']?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="font-weight-bold">Limit insgesamt</p>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $this->limitStatus->total->percent ?>%"
                                 aria-valuenow="<?= $this->limitStatus->total->percent ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="px-2 text-body"><?= $this->limitStatus->total->count ?> von <?= $uploadLimits['total']?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ol class="list-unstyled rideWheatherListItems ">
                <?php foreach ($this->items as $item): ?>
                    <li class="rideWheatherListItem">

                        <div class="row">
                            <div class="col-md-4 col-lg-2 colImage">
                                <a href="<?= JURI::base() . 'component/roadbikelife/ridewheather/show/' . $item->token ?>"
                                   class="d-block item-image img-thumbnail shadow-sm post-image post-image-1-1 rounded-circle border-sm">
                                    <div class="image-wrapper rounded-circle">
                                        <img src="<?= 'media/com_roadbikelife/ridewheather/gpxuploads/' . $item->token . '/' . 'gpx_staticmap.png' ?>"
                                             alt="<?= $item->title ?>" class="img-fluid">
                                    </div>
                                </a>

                            </div>
                            <div class="col-md-8 col-lg-7">
                                <div class="date">
                                    <i class="fal fa-calendar icon-margin-right"></i> Erstellt am:
                                    <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC5')); ?>
                                </div>
                                <h3 class="h4">
                                    <a href="<?= JURI::base() . 'component/roadbikelife/ridewheather/show/' . $item->token ?>">
                                        <?= $item->title ?>
                                    </a>
                                </h3>
                                <?php require 'show/default_show_avg_main.php' ?>

                                <div class="rideInfos">
                                    <div class="row">
                                        <div class="col-6 col-md-3">
                                            <div class="valueLabel">
                                                Streckenlänge
                                            </div>
                                            <?= RoadbikelifeHelper::formatRenderedValue(
                                                $item->distance * 1000, 'distance',
                                                true, true) ?>

                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="valueLabel">
                                                Gesamtanstieg
                                            </div>
                                            <?= RoadbikelifeHelper::formatRenderedValue($item->total_elevation_gain, 'total_elevation_gain', true, true) ?>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="valueLabel">
                                                Startzeit:
                                            </div>
                                            <?php echo JHtml::_('date', $item->start_date, JText::_('DATE_FORMAT_LC5')); ?>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="valueLabel">
                                                Fahrzeit bei <?= $item->speed ?> km/h
                                            </div>
                                            <?= $item->moving_time ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-3 actionsCol">
                                <div class="actionsColBtns">
                                    <a href="<?= JURI::base() . 'component/roadbikelife/ridewheather/show/' . $item->token ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-angle-right icon-margin-right"></i>Wetterbericht öffnen
                                    </a>
                                    <a data-token="<?= $item->token?>" data-toggle="modal" data-target="#changeDateModal"
                                       class="btn btn-sm  btn-outline-light">
                                        <i class="fas fa-edit icon-margin-right"></i>Zeit/Tempo ändern
                                    </a>
                                </div>

                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>

        </div>


    </section>
</div>
<?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_edit.php' ?>
<script>
    $(document).ready(function () {
        $('.rideWheatherListItem [data-toggle="modal"]').click(function() {
            $('#modal-token-input').val($(this).attr('data-token'));
        });
    });
</script>
