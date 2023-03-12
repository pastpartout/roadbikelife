<?php

defined('_JEXEC') or die;

?>
<div class="graphWrapper text-white">
    <div class=" p-2 d-flex align-items-center">
        <div class="detailNumbers">
            <div class="row">
                <?php foreach ($this->flotGraphs as $key => $flotGraph): ?>
                    <div class="col-12  col-lg-6 col-xl-3 <?php if ($key == 'apparentTemperature'): ?>active<?php endif ?>" data-wheather-key="<?= $key ?>">
                        <i
                                class="fa <?= $flotGraph['icon'] ?> icon-margin-right fa-fw"></i>
                        <?= JText::_(strtoupper("COLLAPSIBLE_$key")) . ':'; ?>
                        <span class="<?= $key ?>">-</span>
                        <?= $flotGraph['unit'] ?></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="btn-group graphAnimationControls ml-auto">
            <a class="btn play active">
                <i class="fas fa-fw fa-play"></i>
            </a>
            <a class="btn pause">
                <i class="fas fa-fw fa-pause"></i>
            </a>
        </div>
    </div>

    <div class="tab-content" id="nav-tabStravamap">
        <?php foreach ($this->flotGraphs as $key => $flotGraph): ?>
            <?php //if ($key != 'precipProbability'): ?>
                <div class="tab-pane fade <?php if ($key == 'temperature'): ?>show active<?php endif ?>" role="tabpanel"
                     id="nav-<?= $key ?>">
                    <div id="<?= $key ?>-graph" class="flotGraph"></div>
                    <div class="marker"></div>
                </div>
            <?php //endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<div class="nav navGraphs" id="nav-tab" role="tablist">
    <?php foreach ($this->flotGraphs as $key => $flotGraph): ?>
        <?php //if ($key != 'precipProbability'): ?>
            <a class="nav-item <?php if ($key == 'apparentTemperature'): ?>active<?php endif ?>"
               id="nav-home-tab" data-toggle="tab" href="#nav-<?= $key ?>" data-wheather-key="<?= $key ?>" role="tab"
               aria-controls="nav-home" aria-selected="true">
                <i class="fal fa-fw <?= $flotGraph['icon'] ?> icon-margin-right fa-fw"></i><?= JText::_(strtoupper("COLLAPSIBLE_$key")); ?>
            </a>
        <?php //endif; ?>
    <?php endforeach; ?>
</div>
