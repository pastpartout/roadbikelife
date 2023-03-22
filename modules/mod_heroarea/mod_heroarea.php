<?php
/**
 * @copyright    Copyright © 2019 - All rights reserved.
 * @license        GNU General Public License v2.0
 * @generator    http://xdsoft/joomla-module-generator/
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$doc = Factory::getDocument();

/**
 * $db = Factory::getDBO();
 * $db->setQuery("SELECT * FROM #__mod_heroarea where del=0 and module_id=".$module->id);
 * $objects = $db->loadAssocList();
 */



$app = Factory::getApplication();
$config = $app->getConfig();
$sitename = $config->get('sitename');
$doc = $app->getDocument();
$menu = $app->getMenu();

$fieldComponent     = Factory::getApplication()->bootComponent('com_fields');
$fieldModel = $fieldComponent->getMVCFactory()->createModel('Field','Administrator');
$isSmartphone = ($app->client->mobile === true);
$view = $app->input->getCmd('view', '');


if ($view == 'article'){
    $id = $app->input->getCmd('id', 0);
    if ($id != 0) {
        $article = JTable::getInstance("content");
        $article->load($id);
        $images = json_decode($article->images);
        $images->image_fulltext_alt = $fieldModel->getFieldValue(9, $article->id);
        $imagePosition = $fieldModel->getFieldValue(1, $article->id);
    }
    require JModuleHelper::getLayoutPath('mod_heroarea', 'article');
} else if($view != 'category') {
    require JModuleHelper::getLayoutPath('mod_heroarea', 'component');
}



