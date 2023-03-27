<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/** @var array $stravaFields */
/** @var string $createdDate */

JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_roadbikelife' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'roadbikelife.php');

?>
<?php if (isset($stravaStats)) : ?>
    <div class="text-center personalStravaStats">
        <div class=" postInfos postInfos-strava">
            <p class="small text-white-50">
                Aktualisiert am <?= HTMLHelper::_('date', $createdDate, Text::_('DATE_FORMAT_LC5'))?>
            </p>
            <div class="d-flex justify-content-around flex-wrap">
                <?php foreach ($stravaStats as $key => $field): ?>
                    <?php if ((int)$field !== 0 && $field != ''): ?>
                            <div class="item">
                                <div class="itemInner">
                                    <i class="fal fa-fw text-gradient mb-2 fa-2x <?= $stravaFields[$key]['icon'] ?>"></i>
                                    <span class="h5 mb-0">
                                        <?= RoadbikelifeHelper::formatRenderedValue($field, $key, true, true) ?>
                                    </span>
                                    <span class="small"><?= JTEXT::_(strtoupper($key)) ?></span>
                                </div>
                            </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
            <a href="http://www.strava.com/athletes/<?php echo RoadbikelifeHelper::getParam('strava_profileId') ?>"
               target="_blank" class="strava-link mb-3">
                <img src="<?php echo JUri::base() ?>templates/custom/img/api_logo_pwrdBy_strava_stack_gray.svg"
                     alt="Powered by Strava" class="img-fluid">
                <div class="small">
                    View on Strava
                </div>
            </a>
        </div>
    </div>
<?php endif; ?>
