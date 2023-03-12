<?php

defined('_JEXEC') or die;
$model = $this->getModel();
$windBearingMap = $model->windBearingMap;
$windBearingMap = array_flip($windBearingMap);
?>
<div class="wheatherAvgList postInfos-strava">
    <?php $i = 0; ?>
    <?php foreach ($this->averageItems as $key => $averageItem): ?>
        <?php if ($key != 'temperature' && $key != 'precipProbability'): ?>
            <?php $key = strtolower($key) . '_avg'; ?>
            <?php $dataItem = $item->$key; ?>
            <div class="item">
                <div class="itemInner">
                    <?php if ($key == 'windbearing_avg'): ?>
                        <i class="fal fa-fw  fa-2x  <?= $averageItem['icon'] ?>"
                           style="transform: rotate(<?= $windBearingMap[$item->windbearingname_avg] + 180 . 'deg' ?>) translateZ(0)"></i>
                    <?php else: ?>
                        <i class="fal fa-fw  fa-2x  <?= $averageItem['icon'] ?>"></i>
                    <?php endif ?>
                    <div>
                        <div class="h5 d-block mb-0">
                            <?php if ($key == 'windbearing_avg'): ?>
                                <?= $item->windbearingname_avg ?>
                            <?php else: ?>
                                <?= RoadbikelifeHelper::formatRenderedValue($dataItem, $key, true, true) ?>
                                <?= $averageItem['unit'] ?>
                            <?php endif ?>
                        </div>
                        <div class="valueLabel"><?= JTEXT::_(strtoupper($key)) ?></div>
                    </div>
                </div>
            </div>
        <?php endif ?>

    <?php endforeach ?>
</div>
