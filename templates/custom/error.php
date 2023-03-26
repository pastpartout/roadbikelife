<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$config = JFactory::getConfig();
$sitename = $config->get('sitename');
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $this->error->getCode(); ?>
        - <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="<?php echo $this->baseurl; ?>/templates/custom/css/template.css" rel="stylesheet"/>
    <?php require 'favicons.php' ?>

</head>
<body class="error view-featured p-0">
<header>
    <nav class="navbar navbar-expand-lg ">
        <div class="container">
            <a class="navbar-brand" href="<?= JURI::base() ?>">
                <img src="/templates/custom/img/logo_white.svg?v1" class="logo img-fluid"
                     alt="Logo des roadbikelife Blogs">
            </a>
        </div>
    </nav>
</header>
<main id="component">
    <section id="hero-area">
        <div class="d-none menu-title" style="position: absolute;top:0">Intro</div>
        <div class="fader wrapper">
            <img src="<?php echo JURI::base() ?>templates/custom/img/bg_error.jpeg"
                 alt="<?= $sitename ?> <?= $config->get('MetaDesc') ?>" class="bg-image sectionShadowInset">
            <div class="container">
                <div class="row no-gutters w-100">
                    <div class="col-12 d-flex flex-column">



                        <div class="intro flex-grow-1">
                            <div class="text-white">
                                <h1 class="text-white display-4 mb-5">
                                    <i class="fa fa-sad-tear"></i> Ooops da ging etwas schief!
                                </h1>
                                <h3>
                                    #<?php echo $this->error->getCode(); ?>
                                    &nbsp;<?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                                </h3>

                                <?php
                                jimport('joomla.application.module.helper');
                                $module = JModuleHelper::getModule('mod_search');
                                echo JModuleHelper::renderModule($module);
                                ?>
                            </div>
                        </div>

                        <?php if ($this->debug) : ?>
                            <div class="bg-dark mb-5 shadow-lg">
                                <?php echo $this->renderBacktrace(); ?>
                                <?php // Check if there are more Exceptions and render their data as well ?>
                                <?php if ($this->error->getPrevious()) : ?>
                                    <?php $loop = true; ?>
                                    <?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
                                    <?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
                                    <?php $this->setError($this->_error->getPrevious()); ?>
                                    <?php while ($loop === true) : ?>
                                        <p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                                        <p><?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                                        <?php echo $this->renderBacktrace(); ?>
                                        <?php $loop = $this->setError($this->_error->getPrevious()); ?>
                                    <?php endwhile; ?>
                                    <?php // Reset the main error object to the base error ?>
                                    <?php $this->setError($this->error); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <hr>
                        <div class="text-center p-3">
                            <a class="btn btn-primary shadow-lg"
                               href="<?php echo $this->baseurl; ?>/index.php"
                               title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>">
                                <i class="fa fa-home mr-2"></i>
                                <?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<footer>
    <?php
    jimport('joomla.application.module.helper');
    $module = JModuleHelper::getModuleById('95');
    echo JModuleHelper::renderModule($module);
    ?>
</footer>


</body>

</html>

