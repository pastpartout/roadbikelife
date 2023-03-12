<?php
defined('_JEXEC') or die;
/** @var  $fieldsAvailable : boolean */
?>
<?php if ($fieldsAvailable === true) : ?>
    <div class="articleInfoLinks">
        <div class="p-3">
            <?php if ($module->showtitle && $fieldsAvailable === true): ?>
                <h3 class="rblHeadline">
            <span>
                  <?= $module->title; ?>
            </span>
                </h3>
            <?php endif; ?>
            <div class="postInfos list-unstyled d-flex justify-content-around">
                <?php foreach ($fields as $field): ?>
                    <?php if ($field->value != '') : ?>
                        <?php if ($field->name == 'komooot-url'): ?>
                                <div class="item small border-0">
                                    <a href="<?= $field->value ?>" target="_blank" class="itemInner">
                                        <i class="fal fa-fw fa-map "></i>
                                        <div class="h5  mb-0 text-white">
                                            Komoot-Link
                                        </div>
                                    </a>
                                </div>
                        <?php endif ?>
                        <?php if ($field->name == 'stravaactivityid'): ?>
                                <div class="item small border-0">
                                    <a href="https://www.strava.com/activities/<?= $field->value[0] ?>" target="_blank"
                                       class="itemInner strava-link">
                                        <i class="fab fa-fw fa-strava "></i>
                                        <div class="h5 mb-0 text-white">
                                            Strava Aktivität
                                        </div>
                                    </a>
                                </div>
                        <?php endif ?>
                    <?php endif ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
