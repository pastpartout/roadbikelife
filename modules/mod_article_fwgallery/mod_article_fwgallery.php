<?php
/**
 * @copyright	Copyright © 2019 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$app = Factory::getApplication();
$doc = $app->getDocument();
$id = $app->input->getCmd('id', 0);
$knownCameraPrefixes = ['PHOTO','CIMG','DSC','DSCF','DSCN','DSC','DUW','IMG','JD','MGP','S700','PICT','vlcsnap','KIF','IMAG'];
$captionPrefix = 'Caption:';
$images = [];

if ($id !== 0) {
    $article = JTable::getInstance("content");
    $article->load($id);

	$fieldComponent     = Factory::getApplication()->bootComponent('com_fields');
	$fieldModel = $fieldComponent->getMVCFactory()->createModel('Field','Administrator');

    $galleryId = $fieldModel->getFieldValue(3, $article->id);
    $stravaActivityId = (int)$fieldModel->getFieldValue(5, $article->id);
}

if((int)$galleryId > 0) {
    $db = JFactory::getContainer()->get('DatabaseDriver');
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__fwsg_file'));
    $query->where(array($db->quoteName('category_id') . ' = '.$galleryId));
    $query->order('ordering ASC');
    $db->setQuery($query);
    $images = $db->loadObjectList();

    foreach ($images as $image) {
        if(strpos($image->name,$captionPrefix) !== false) {
            $image->has_caption = true;
            $image->name = preg_replace('/^Caption:/i','',$image->name);
        }
    }
}

require JModuleHelper::getLayoutPath('mod_article_fwgallery', $params->get('layout', 'default'));