<?php
defined('_JEXEC') or die;
JLoader::register('RoadbikelifeModelLike', 'components/com_roadbikelife/models/like.php');
$RoadbikelifeModelLike = new RoadbikelifeModelLike;
extract($displayData);

$commentsCount = $RoadbikelifeModelLike->getCommentsCount($item->id,);
$likesCount = $RoadbikelifeModelLike->getLikesCount($item->id, 'content');
$likesDisabled = $RoadbikelifeModelLike->getLikesIsDisabled($item->id, 'content');
JFactory::getDocument()->addScript('modules/mod_like_button/assets/js/script.js');

?>

<div class="btn-likes-and-comments">
    <?= JLayoutHelper::render('rbl.common.btn_like',$displayData); ?>
    <span class="btn-likes-and-comments-count">
        <i class="fal fa-comments mr-2"></i><?php echo $commentsCount ?>
    </span>
</div>
