<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$config = JFactory::getConfig();
$sitename = $config->get('sitename');
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
<body class="error view-featured">


<header>
    <nav class="navbar navbar-expand-lg ">
        <div class="container">
            <a class="navbar-brand col-lg-3" href="<?= JURI::base()?>">
                <img src="/templates/custom/img/logo_white.svg?v1" class="logo img-fluid" alt="Logo des roadbikelife Blogs">
            </a>
        </div>
    </nav>
</header>
<main id="component">
    <section id="hero-area">
        <div class="d-none menu-title" style="position: absolute;top:0">Intro</div>
        <div class="fader wrapper">
            <img src="<?php echo JURI::base() ?>templates/custom/img/bg_hero_area.jpg"
                 alt="<?= $sitename ?> <?= $config->get('MetaDesc') ?> Titelbild - " class="bg-image sectionShadowInset">
            <div class="container">
                <div class="row no-gutters w-100">
                    <div class="col-12">
                        <div class="intro">
                            <div class="text">
                                <h1 class="text-white mb-3">
                                    Ooops da ging etwas schief!
                                </h1>
                                <h1>
                                    #<?php echo $this->error->getCode(); ?>
                                    &nbsp;<?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                                </h1>
                                <div class="text-center p-3">
                                    <a class="btn btn-primary btn-lg shadow-lg"
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
            </div>
        </div>
    </section>
</main>
</body>
</html>

