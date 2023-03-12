<?php

defined('_JEXEC') or die;
?>
<div class="wheatherStartFinish ">
    <div class="row h-100 no-gutters">
        <div class="col-md-6">
            <h3>
                <div class="small">Wetter am</div>
                <div class="text-uppercase">Start</div>
            </h3>
            <div class="postInfos-strava">
                <?php foreach ($this->averageItems as $key => $averageItem): ?>
                    <?php $dataItem = $item->wheather[0]->data; ?>
                    <div class="item">
                        <div class="itemInner">
                            <?php if ($key == 'windBearing'): ?>
                                <i class="fal fa-fw  fa-2x  <?= $averageItem['icon'] ?>"
                                   style="transform: rotate(<?= $dataItem->$key+180 . 'deg' ?>) translateZ(0)"></i>
                            <?php else: ?>
                                <i class="fal fa-fw  fa-2x  <?= $averageItem['icon'] ?>"></i>
                            <?php endif ?>
                            <div>

                                <div class="h5 d-block mb-0">
                                    <?php if ($key == 'windBearing'): ?>
                                        <?= $this->showModel->getWindBearingName($dataItem->windBearing); ?>
                                    <?php else: ?>
                                        <?= RoadbikelifeHelper::formatRenderedValue($dataItem->$key, $key, true, true) ?>
                                        <?= $averageItem['unit'] ?>
                                    <?php endif ?>
                                </div>
                                <div class="valueLabel"><?= JTEXT::_(strtoupper($key)) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

        </div>
        <div class="col-md-6 colFinish">
            <h3>
                <div class="small">Wetter am</div>
                <div class="text-uppercase">Ziel</div>
            </h3>
            <div class="postInfos-strava">
                <?php foreach ($this->averageItems as $key => $averageItem): ?>
                    <?php $dataItem = $item->wheather[count($item->wheather) - 1]->data; ?>
                    <div class="item">
                        <div class="itemInner">
                            <?php if ($key == 'windBearing'): ?>
                                <i class="fal fa-fw  fa-2x  <?= $averageItem['icon'] ?>" style="transform: rotate(<?= $dataItem->$key+180 . 'deg' ?>)"></i>
                            <?php else: ?>
                                <i class="fal fa-fw  fa-2x  <?= $averageItem['icon'] ?>"></i>
                            <?php endif ?>
                            <div>
                                <div class="h5 d-block mb-0">
                                    <?php if ($key == 'windBearing'): ?>
                                        <?= $this->showModel->getWindBearingName($dataItem->windBearing); ?>
                                    <?php else: ?>
                                        <?= RoadbikelifeHelper::formatRenderedValue($dataItem->$key, $key, true, true) ?>
                                        <?= $averageItem['unit'] ?>
                                    <?php endif ?>
                                </div>
                                <div class="valueLabel"><?= JTEXT::_(strtoupper($key)) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
