<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.dentika
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$this->language  = $doc->language;
$this->direction = $doc->direction;
$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

$this->language  = $doc->language;
$this->direction = $doc->direction;


// Detecting Active Variables
$option    = $app->input->getCmd('option', '');
$view      = $app->input->getCmd('view', '');
$layout    = $app->input->getCmd('layout', '');
$task      = $app->input->getCmd('task', '');
$itemid    = $app->input->getCmd('Itemid', '');
$sitename  = $app->getCfg('sitename');
$site_desc = $app->getCfg('MetaDesc');


// Add Stylesheets
$doc->addStyleSheet('https://fonts.googleapis.com/css?family=Crete+Round|Open+Sans:400,400i,600,700');
$doc->addStyleSheet('templates/' . $this->template . '/css/template.css');
$doc->addScript('templates/' . $this->template . '/js/fastclick.js');
// Load optional rtl Bootstrap css and Bootstrap bugfixes
//JHtmlBootstrap::loadCss($includeMaincss = false, $this->direction);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>"
      lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" class="component-tmpl">
<head>
    <jdoc:include type="head"/>
    <!--[if lt IE 9]>
	<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
<![endif]-->
    <meta name="viewport" content="width=device-width, user-scalable=no"/>
</head>
<body class="contentpane
 <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '');
?>"
">
<jdoc:include type="message"/>
<jdoc:include type="component"/>
</body>
</html>
