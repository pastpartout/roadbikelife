<?php

defined('_JEXEC') or die;
?>
<div class="clothingRecs">
    <p class="font-weight-bold">
        Kleidungsempfehlung
        <i class="fa fa-info-circle fa-ml-2"
           data-toggle="tooltip"
           title="Die Kleidungsempfehlung richtet sich nach der niedrigsten Temperatur auf der Strecke und
           berücksichtigt dabei Maximalwerte sowie Regenwahrscheinlichekit und Sonneneinstrahlung um dir die Auswahl der richtigen Teile
        zu erleichtern."></i>
    </p>

    <?php foreach ($item->clothing_recs as $clothingItemName => $clothingItemScore): ?>
        <div class="small text-white pt-1  mb-1 text-center"><?= JText::_(strtoupper($clothingItemName)) ?></div>
        <div class="range-slider">
            <div class="small">
                <div class="valueLabel">Nein</div>
            </div>
            <div class="track">
                <div class="marker" style="left: <?= $clothingItemScore ?>%"></div>
            </div>
            <div class="small">
                <div class="valueLabel">Ja</div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
