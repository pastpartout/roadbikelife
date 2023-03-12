<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


$app = Factory::getApplication();
$doc = $app::getDocument();
$this->language = $doc->language;



// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');
$site_desc = $app->getCfg('MetaDesc');
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
$config = JFactory::getConfig();

$user = JFactory::getUser();

$menu = $app->getMenu();
$lang = JFactory::getLanguage();
jimport('joomla.html.parameter');
$params = $app->getMenu()->getActive()->params;
//$image = $params->get('image');

$session = JFactory::getSession();

//$ogImage = $this->params->get('og_image');
//$sizes = getimagesize(JURI::base().$ogImage);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <jdoc:include type="head" />
</head>
<body class="offline view-featured">
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
                                    Sorry! roadbikelife.de ist gerade offline.
                                </h1>
                                <div>
									<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) !== '') : ?>
                                        <p>
											<?php echo $app->get('offline_message'); ?>
                                        </p>
									<?php elseif ($app->get('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) !== '') : ?>
                                        <p>
											<?php echo JText::_('JOFFLINE_MESSAGE'); ?>
                                        </p>
									<?php endif; ?>
                                </div>

                                <jdoc:include type="message"/>
                                <form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
                                    <div class="shadow mt-3 rounded bg-white mb-4">
                                        <div class="rounded  overflow-hidden">
                                            <div class="input-group input-group-lg rounded-top">
                                    <span class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0">
                                            <i class="fa fa-at large fa-fw"></i>
                                        </span>
                                    </span>
                                                <input id="username" type="text" required class="form-control rounded-0 input-lg"
                                                       name="username" value="" placeholder="E-Mail-Adresse">
                                            </div>
                                            <div class="input-group input-group-lg rounded-bottom ">
                                    <span class="input-group-prepend rounded-0">
                                        <span class="input-group-text border-top-0   rounded-0">
                                            <i class="fa fa-lock large fa-fw"></i>
                                        </span>
                                    </span>
                                                <input id="password" type="password" required class="form-control border-top-0 rounded-0 input-lg"
                                                       name="password" value="" placeholder="Passwort">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="option" value="com_users" />
                                    <input type="hidden" name="task" value="user.login" />
                                    <input type="hidden" name="return" value="<?php echo base64_encode(JUri::base()); ?>" />
									<?php echo JHtml::_('form.token'); ?>
                                    <div class="text-center form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow">
                                            <i class="fa fa-sign-in"></i>
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="text-center">
								<?php echo JLayoutHelper::render('joomla.blog.btn_strava_follow_club'); ?>
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
