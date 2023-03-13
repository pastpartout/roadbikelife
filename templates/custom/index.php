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
ini_set('memory_limit', '512M');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $this->language; ?>"
      lang="<?= $this->language; ?>" dir="<?= $this->direction; ?>"
      class=""
      data-baseurl="<?= JURI::base() ?>" data-gaid="<?= $this->params->get('google_tracking_code') ?>"
>
<head>
    <meta name="viewport" content="width=device-width, user-scalable=yes"/>
    <meta name="city" content="Leipzig">
    <meta name="country" content="Germany">
    <meta name="page-topic" content="Sport">
    <meta name="identifier-URL" content="<?= JUri::base() ?>">
    <meta name="copyright" content="<?= $sitename ?>">
    <meta name="owner" content="<?= $sitename ?>">
    <meta name="designer" content="Stephan Riedel">
	<?php require 'favicons.php' ?>
    <jdoc:include type="head"/>
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
<?php if ($view != 'ridewheather' && $layout != 'customrblfrontpage'): ?>
    <footer>
        <div class="container-fluid px-0">

            <div class="articleModules">
                <div class="row no-gutters">
                    <jdoc:include type="modules" name="footer-top" style="bootstrap3"/>
                </div>
            </div>
            <div class="menuModules">
                <div class="row no-gutters">
                    <jdoc:include type="modules" name="footer-bottom" style="bootstrap3"/>
                </div>
            </div>
        </div>

    </footer>
<?php endif ?>
<jdoc:include type="modules" name="debug" style="none"/>
</body>
</html>
