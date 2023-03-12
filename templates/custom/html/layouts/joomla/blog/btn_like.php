<?php
defined('_JEXEC') or die;
JLoader::register('RoadbikelifeModelLike', 'components/com_roadbikelife/models/like.php');
$RoadbikelifeModelLike = new RoadbikelifeModelLike;
$likesCount = $RoadbikelifeModelLike->getLikesCount($displayData->id, 'content');
$likesDisabled = $RoadbikelifeModelLike->getLikesIsDisabled($displayData->id, 'content');
JFactory::getDocument()->addScript('modules/mod_like_button/assets/js/script.js');
?>

<span class="btn  btnLike text-danger <? if ($likesDisabled === true): ?>liked<?php endif ?>"
      data-content-id="<?= $displayData->id ?>" data-type="content">
    <i class="fal fa-thumbs-up icon-margin-right"></i>
    <span class="content">
    <span class="number"><?= $likesCount ?> </span>
        Like<?php if ((int)$likesCount > 1 || (int)$likesCount == 0) echo 's'; ?>
    </span>
</span>
