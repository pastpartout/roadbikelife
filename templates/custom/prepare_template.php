<?php
/** @var Joomla\CMS\Document\HtmlDocument $this */
JLoader::register('Mobile_Detect', JPATH_ROOT . '/vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php');

use Joomla\CMS\Factory;

$detect = new Mobile_Detect;
$app            = Factory::getApplication();
$doc            = $app->getDocument();
$this->language = $doc->language;
$wa             = $this->getWebAssetManager();

// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $doc->getTitle();

if (strpos(JURI::base(), 'localhost') !== false) {
	$wa->registerAndUseStyle('template_Css','templates/' . $this->template . '/css/template_debug.css');
} else {
	$wa->registerAndUseStyle('template_Css','templates/' . $this->template . '/css/template.css',['rel' => 'preload']);
}

JHtml::_('jquery.framework');
$wa->registerAndUseStyle('fancybox_Css','templates/' . $this->template . '/css/jquery.fancybox.min.css',['rel' => 'preload']);
$wa->registerAndUseScript('popper','templates/' . $this->template . '/js/popper.js');
$wa->registerAndUseScript('bootstrap','templates/' . $this->template . '/js/bootstrap.min.js');
$wa->registerAndUseScript('modernizr','templates/' . $this->template . '/js/modernizr.js');
$wa->registerAndUseScript('detectizr','templates/' . $this->template . '/js/detectizr.min.js');
//$wa->addScript('templates/' . $this->template . '/js/cookie_consent_script.js');
$wa->registerAndUseScript('functions_js','templates/' . $this->template . '/js/functions.js');
$wa->addInlineScript("window.baseUrl = '" . JURI::base() . "';");

$user = $app->getIdentity();
$menu = $app->getMenu();
$lang = $app->getLanguage();
$langTag = $lang->getTag();
$session = $app->getSession();
$messages = $app->getMessageQueue();