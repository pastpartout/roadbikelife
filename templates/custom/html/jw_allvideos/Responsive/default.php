<?php
/**
 * @version    6.1.0
 * @package    AllVideos (plugin)
 * @author     JoomlaWorks - https://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2020 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="avPlayerWrapper<?php echo $output->mediaTypeClass; ?>" <?php echo $maxwidth; ?>>
    <div class="avPlayerContainer" id="<?php echo $output->playerID; ?>">
        <div class="avPlayerBlock">
            <?php if ($plg_tag == 'youtube' && $pluginParams->get('ytnocookie') === '1'): ?>
                <img src="https://img.youtube.com/vi/<?= $ytQuery ?>/hqdefault.jpg" class="bg-img">
                <span class="show-data-privacy-info">
                    <img src="templates/custom/img/fa-play.svg" class="img-responsive play-svg">
                </span>
                <div class='data-privacy-info--text fade out'>
                    <div>
                        <a class='btn btn-secondary btn-default btn-sm btn-play d-inline-flex align-items-center btn-play-yt-video'
                           data-href='https://www.youtube-nocookie.com/embed/<?= $ytQuery ?>?autoplay=1&enablejsapi=1&version=3'>
                            <img src="templates/custom/img/fa-play.svg" class="img-responsive mr-2" style="width: .5em">
                            Video starten
                        </a>
                        <div class="pt-3">
                        <p>
                            Datenschutzhinweis: Dieses Video ist im erweiterten Datenschutzmodus von Youtube eingebunden, der das Setzen von
                            Youtube-Cookies solange blockiert, bis ein aktiver Klick auf die Wiedergabe erfolgt.
                        </p>
                        <p>
                            <a aria-expanded="false" class="btn  btn-xsm btn-outline-light "
                               data-toggle="collapse" data-target="#data-privacy-collapse-text-<?= $ytQuery ?>">
                               <i class="falfa-angle-right fa-fw"></i>Mehr erfahren </a>
                        </p>
                        <div class="collapse" id="data-privacy-collapse-text-<?= $ytQuery ?>">
                            <p>Mit Klick
                                auf den Wiedergabe-Button erteilen Sie Ihre Einwilligung darin, dass Youtube auf dem von
                                Ihnen
                                verwendeten Endgerät Cookies setzt, die auch einer Analyse des Nutzungsverhaltens zu
                                Marktforschungs- und Marketing-Zwecken dienen können.
                                Näheres zur Cookie-Verwendung durch Youtube finden Sie in der Cookie-Policy von Google
                                unter
                                https://policies.google.com/technologies/types?hl=de.
                            </p>
                        </div>
                        </div>

                    </div>
                </div>
            <?php else: ?>
                <img src="" class="bg-img">
                <?php echo $output->player; ?>
            <?php endif ?>
        </div>

        <?php if (($allowVideoDownloading && $output->mediaType == 'video') || ($allowAudioDownloading && $output->mediaType == 'audio')): ?>
            <div class="avDownloadLink">
                <a target="_blank" href="<?php echo $output->source; ?>"
                   download><?php echo JText::_('JW_PLG_AV_DOWNLOAD'); ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>
