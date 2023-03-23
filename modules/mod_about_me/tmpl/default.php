<?php
defined('_JEXEC') or die;
?>
<div class="about-me">
    <div class="text-left align-items-start">
        <div class="small">
            <div class="float-right col-4 pl-3 mb-3">
                <div class="item-image shadow-sm
                 post-image no-hover-zoom post-image-1-1 rounded-circle">
                    <div class="image-wrapper rounded-circle border-sm">
                        <img src="/images/hero-area/stephan-riedel-portrait.jpg" loading="lazy"
                             alt="Der Autor von  <?= $sitename ?>: Stephan Riedel"
                             class="img-fluid">
                    </div>
                </div>
            </div>
            <?= $articleAboutMe->introtext ?>
        </div>
        <hr>
        <div class="followBtnWrapper text-center">
            <div class="mb-3">
                <?php echo JLayoutHelper::render('rbl.common.btn_strava_follow_me'); ?>
            </div>
            <div class="mb-3">
                <?php echo JLayoutHelper::render('rbl.common.btn_strava_follow_club'); ?>
            </div>
        </div>
    </div>
</div>