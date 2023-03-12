<?php

defined('_JEXEC') or die;

$headerTitle = 'Route hinzufügen';
JHtml::_('jquery.framework');
$document = JFactory::getDocument();
$document->addScript('/media/com_roadbikelife/js/moment-with-locales.min.js');
$document->addScript('/media/com_roadbikelife/js/bootstrap-datetimepicker.min.js');
$document->addScript('/media/com_roadbikelife/js/create_ride.js');

?>


<div class="rideWheather rideWheatherCreate">
    <img class="bgImage" src="media/com_roadbikelife/img/bg_rw.jpg">
    <?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header.php' ?>
    <section class="row no-gutters colWrapper">
        <div class="container">
            <div class="introWrapper">
                <h1 class="h3">
                    <?php //require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header_logo.php' ?>
                    <div class="pt-4 mb-4">
                       Uploadquelle wählen
                    </div>
                </h1>
            </div>
            <div class="accordion shadow" id="accordionCreate">
                <div class="card">
                    <div class="card-header" id="headingOne"
                         data-toggle="collapse" data-target="#collapseGpx" aria-expanded="true" aria-controls="collapseGpx">
                        <i class="fal fa-upload icon-margin-right"></i>Upload per GPX-Datei
                    </div>

                    <div id="collapseGpx" class="collapse <?php if (!isset($this->stravaRoutes)): ?>show<?php endif; ?>" aria-labelledby="headingOne" data-parent="#accordionCreate">
                        <div class="card-body">

                            <form method="post" enctype="multipart/form-data">
                                <div class="input-group mb-3">

                                    <div class="custom-file" style="overflow: hidden">
                                        <? $field = $this->form->getField('gpxfile'); ?>
                                        <?= $field->input; ?>
                                        <label class="custom-file-label text-nowrap" for="inputGroupFile01">Wähle
                                            GPX-Datei</label>
                                    </div>
                                </div>

                                <? $field = $this->form->getField('date_start'); ?>
                                <?= $field->label; ?>
                                <div class="calendarWrapper">
                                    <div id="date_start" class="datePicker"></div>
                                    <input type="hidden" name="date_start" class="date_start_input">
                                </div>

                                <?= $this->form->renderField('speed'); ?>
                                <div class="mt-3 pt-3 ">
                                    <div class="d-flex w-100 justify-content-center text-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-file-upload icon-margin-right"></i>Upload starten
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="task" value="ridewheather.gpxupload">
                            </form>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingOne"
                         data-toggle="collapse" data-target="#collapseStrava" aria-expanded="true" aria-controls="collapseStrava">
                        <i class="fab fa-strava icon-margin-right"></i>
                        Upload per Strava
                    </div>

                    <div id="collapseStrava" class="collapse <?php if (isset($this->stravaRoutes)): ?>show<?php endif; ?>" aria-labelledby="headingOne" data-parent="#accordionCreate">
                        <div class="card-body text-center">

                            <?php if (isset($this->stravaRoutes)): ?>
                                <form method="post" enctype="multipart/form-data">

                                <ul class="list-group stravaRoutes">
                                    <?php foreach ($this->stravaRoutes as $key => $stravaRoute): ?>
                                        <li class="list-group-item">
                                            <div class="row ">
                                                <div class="col-2 col-md-1 h-100 d-flex align-items-center justify-content-center">
                                                    <div class="custom-control custom-radio mb-2">
                                                        <input type="radio" name="strava_route"
                                                               value="<?= $stravaRoute->id_str ?>"
                                                               <?php if($key == 0):?>checked<?php endif; ?>
                                                               class="custom-control-input" id="customControlValidation<?= $stravaRoute->id ?>">
                                                        <label class="custom-control-label" for="customControlValidation<?= $stravaRoute->id ?>"></label>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-md-8">
                                                    <div class="valueLabel date mb-1 text-black-50">
                                                        <i class="fas fa-calendar icon-margin-right"></i><?= JFactory::getDate($stravaRoute->created_at)->format('d.m.Y H:i') ?>
                                                    </div>
                                                    <div class="font-weight-bold name">
                                                        <?= $stravaRoute->name ?>
                                                    </div>
                                                    <?php if (isset($stravaRoute->description)): ?>
                                                        <div class="small">
                                                            <?= $stravaRoute->description ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-3 col-md-2 colImg">
                                                    <div class="item-image img-thumbnail shadow-sm post-image post-image-1-1 rounded-circle border-sm">
                                                        <div class="image-wrapper rounded-circle">
                                                            <img src="<?= $this->model->getGmapsImageUrl('200x200',urlencode($stravaRoute->map->summary_polyline)) ?>" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                    <? $field = $this->form->getField('date_start'); ?>
                                    <?= $field->label; ?>
                                    <div class="calendarWrapper">
                                        <div id="date_start" class="datePicker"></div>
                                        <input type="hidden" name="date_start" class="date_start_input" id="date_start_input_strava">
                                    </div>

                                    <?= $this->form->renderField('speed'); ?>
                                    <div class="mt-3 pt-3 ">
                                        <div class="d-flex w-100 justify-content-center text-center">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-file-upload icon-margin-right"></i>Upload starten
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="task" value="ridewheather.stravaupload">
                            </form>
                            <?php else : ?>
                                <a href="<?= $this->stravaLoginButtonUrl ?>">
                                    <img class="img-fluid" src="media/com_roadbikelife/img/btn_strava_connectwith_orange.svg">
                                </a>
                            <?php endif; ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

