<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
// Getting params from template
require_once __DIR__ . '/prepare_template.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $this->language; ?>"
      lang="<?= $this->language; ?>" dir="<?= $this->direction; ?>"
      class=""
      data-baseurl="<?= JURI::base() ?>" data-gaid="<?= $this->params->get('google_tracking_code') ?>"
>
<head>
    <meta name="viewport" content="width=device-width, user-scalable=no"/>
    <meta name="city" content="Leipzig">
    <meta name="country" content="Germany">
    <meta name="page-topic" content="Sport">
    <meta name="identifier-URL" content="<?= JUri::base() ?>">
    <meta name="copyright" content="<?= $sitename ?>">
    <meta name="owner" content="<?= $sitename ?>">
    <meta name="designer" content="Stephan Riedel">
    <?php require 'favicons.php' ?>
    <jdoc:include type="head"/>
    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//matomo.roadbikelife.de/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '2']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
</head>
<body class="site
     <?= $option
. ' view-' . $view
. ($layout ? ' layout-' . $layout : ' no-layout')
. ($task ? ' task-' . $task : ' no-task')
. ($itemid ? ' itemid-' . $itemid : '');
?>
 "
>
<?php if (is_array($messages)): ?>
    <div class="bg-dark messageWrapper">
        <jdoc:include type="message"/>
    </div>
<?php endif ?>
<?php if ($view != 'ridewheather'): ?>
    <jdoc:include type="modules" name="content-top" style="bootstrap3"/>
<?php endif ?>
<main>
    <section id="component">
        <jdoc:include type="component"/>
        <jdoc:include type="modules" name="content-bottom" style="bootstrap3"/>
    </section>
</main>
<?php if ($view != 'ridewheather'): ?>
    <footer>
        <div class="container-fluid px-0">
            <div class="row no-gutters">
                <jdoc:include type="modules" name="footer-bottom" style="bootstrap3"/>
            </div>
        </div>
    </footer>
<?php endif ?>
<jdoc:include type="modules" name="debug" style="none"/>
<jdoc:include type="modules" name="bottombar" style="bootstrap3"/>
</body>
</html>
