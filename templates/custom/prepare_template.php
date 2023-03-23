<?php
/** @var Joomla\CMS\Document\HtmlDocument $this */
use Joomla\CMS\Factory;
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

if (strpos(JURI::base(), 'roadbikelife.de') !== false) {
    $wa->registerAndUseStyle('rbl_template_css',JUri::base().'templates/' . $this->template . '/css/template.css');
} else {
	$wa->registerAndUseStyle('rbl_template_css','/templates/' . $this->template . '/css/template.css?v='.strtotime('now'));
}

JHtml::_('jquery.framework');
$wa->registerAndUseStyle('fancybox_Css','templates/' . $this->template . '/css/jquery.fancybox.min.css',['rel' => 'preload']);
$wa->registerAndUseScript('popper','templates/' . $this->template . '/js/popper.js');
$wa->registerAndUseScript('bootstrap','templates/' . $this->template . '/js/bootstrap.min.js');
$wa->registerAndUseScript('modernizr','templates/' . $this->template . '/js/modernizr.js');
$wa->registerAndUseScript('detectizr','templates/' . $this->template . '/js/detectizr.min.js');
$wa->registerAndUseScript('functions_js','templates/' . $this->template . '/js/functions.js');
$wa->addInlineScript("window.baseUrl = '" . JURI::base() . "';");

$user = $app->getIdentity();
$menu = $app->getMenu();
$lang = $app->getLanguage();
$langTag = $lang->getTag();
$session = $app->getSession();
$messages = $app->getMessageQueue();