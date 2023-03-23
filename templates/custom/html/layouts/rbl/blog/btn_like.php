<?php
defined('_JEXEC') or die;
JLoader::register('RoadbikelifeModelLike', 'components/com_roadbikelife/models/like.php');
$RoadbikelifeModelLike = new RoadbikelifeModelLike;
extract($displayData);

$likesCount = $RoadbikelifeModelLike->getLikesCount($item->id, 'content');
$likesDisabled = $RoadbikelifeModelLike->getLikesIsDisabled($item->id, 'content');
JFactory::getDocument()->addScript('modules/mod_like_button/assets/js/script.js');
?>

<span class="btn  btnLike text-danger <? if ($likesDisabled === true): ?>liked<?php endif ?>"
      data-content-id="<?= $item->id ?>" data-type="content">
    <i class="fal fa-thumbs-up icon-margin-right"></i><span class="content"><span class="number"><?= $likesCount ?> </span></span>
</span>
