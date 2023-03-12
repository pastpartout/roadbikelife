<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\Event\OnGetApiFields;
use Joomla\Event\Event;

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.memberarea');
JLoader::register('RoadbikelifeModel', __DIR__ . '/roadbikelife.php');
JLoader::register('RoadbikelifeHelper', __DIR__ . '../helpers/roadbikelife.php');
JLoader::register('fwGalleryModelFile', JPATH_BASE . '/components/com_fwgallery/models/file.php');
JLoader::register('MainModelMain', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jotcache' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'main.php');
JLoader::register('MainModelRecache', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jotcache' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'recache.php');

/**
 * Methods supporting a list of Roadbikelife records.
 *
 * @since  1.6
 */
class RoadbikelifeModelCreatecontent extends JModelLegacy
{


    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */

    private $galleryId;
    private $contentId;
    private $galleryFiles = false;
    private $galleryAliasPrefix = 'contentgallery-';
    private $gpxFile;

    public function __construct($config = array())
    {
        parent::__construct($config);
	    $this->errors = [];
    }


    public function save()
    {
        $this->createContent();
        $contentItemImages = $this->saveImages();
        $this->saveContent($contentItemImages);
        $contentFieldsSaved = $this->saveFields();
        $this->saveGalleryAltTexts();
        $this->saveGalleryOrdering();
        $this->queueErrors();
//        $this->redirectAfterSave($contentItemSaved);
        return $this->contentId;
    }

    private function getArticleImageFolder()
    {
        $jinput = JFactory::getApplication()->input;
        $postedId = $this->contentId;
        $imageFolderName = 'images/content/' . $postedId;

        if (!is_dir(JPATH_ROOT . '/' . $imageFolderName)) {
            mkdir(JPATH_ROOT . '/' . $imageFolderName, 0755, true);
        }
        return $imageFolderName;
    }

    private function getArticleGpxFolder()
    {
        $jinput = JFactory::getApplication()->input;
        $postedId = $jinput->get('id', 0, 'int');
        $imageFolderName = 'gpx-files/' . $postedId;

        if (!is_dir(JPATH_ROOT . '/' . $imageFolderName)) {
            mkdir(JPATH_ROOT . '/' . $imageFolderName, 0755, true);
        }
        return $imageFolderName;
    }

    private function correctImageOrientation($filename)
    {
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($filename);
            if ($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if ($orientation != 1) {
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }
                    // then rewrite the rotated image back to the disk as $filename
                    imagejpeg($img, $filename, 95);
                } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists
    }

    private function getFieldsData($fieldsetName)
    {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            $fieldsetName => 'RAW',
        ));
        return $fieldsData[$fieldsetName];
    }

    private function saveImages()
    {
        jimport('joomla.filesystem.file');

        $jinput = JFactory::getApplication()->input;
        $articleFolder = $this->getArticleImageFolder();
        $item = $this->getItem();

        $files = $jinput->files->get('images');

        $returnArray = [];

        foreach ($this->getForm($item)->getFieldset('images') as $field) {
	        if ($files[$field->fieldname]['size'] > 0) {
		        if ($item->images[$field->fieldname] != '') {
			        unlink(JPATH_ROOT . '/' . $item->images[$field->fieldname]);
		        }
		        $image = $files[$field->fieldname];
		        $image['copy_path'] = JPATH_ROOT . '/' . $articleFolder . '/' . $image['name'];
		        $image['final_path'] = $articleFolder . '/' . $image['name'];
		        JFile::copy($image['tmp_name'], $image['copy_path']);
		        $this->correctImageOrientation($image['copy_path']);
	        }

	        $returnArray[$field->fieldname] = $image;

        }



	    if ($files['gallery_images'][0]['size'] > 0) {
            $fwGalleryModelFile = new fwGalleryModelFile();
            $this->galleryId = $this->createGallery();
            $this->galleryFiles = true;

            foreach ($files['gallery_images'] as $image) {
                if ($image['size'] > 0) {
                    $this->installFwFiles($image, $this->galleryId);
                }
            }

        }


        return $returnArray;

    }

    function installFwFiles($image, $galleryId)
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $success = $errors = 0;
        require_once JPATH_ROOT . '/components/com_fwgallery/helpers/helper.php';
        require_once JPATH_ROOT . '/components/com_fwgallery/helpers/fwsgimage.php';


        if ($images = $input->files->get('images')) {
            jimport('joomla.filesystem.file');
            $user = JFactory::getUser();

            $exts = array('gif', 'png', 'jpg', 'jpeg');
            if (!empty($image['name']) and empty($image['error'])) {
                JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fwgallery/tables');
                $file = JTable::getInstance('file', 'Table', array());
                $file->id = 0;
                $file->type = 'image';
                $file->name = JFile::stripExt($image['name']);
                $file->published = 1;
                $file->user_id = $user->id;
                $file->category_id = $galleryId;
                $file->access = 0;

                if ($file->check() and $file->store()) {
                    JHTML::_('fwSgImage.store', $file, $image);
                    $app->triggerEvent('storeFile', array('com_fwgallery', $file, $isnew = true));
                    $success++;
                } else {
                    $errors++;
                }
            } else {
                $errors++;
            }
        }
        $this->setError(JText::sprintf('FWG_BATCH_UPLOAD_RESULT', $success, ($success + $errors), 'contentgallery-' . $galleryId));
        return $success;
    }

    private function createGallery()
    {
        $tableName = '#__fwsg_category';


        $fieldsData = $this->getFieldsData('general');
        $title = $fieldsData['title'];
        $contentId = $fieldsData['id'];
        $galleryAlias = $this->galleryAliasPrefix . $contentId;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($tableName));
        $query->where($db->quoteName('alias') . ' = ' . $db->quote($galleryAlias));
        $db->setQuery($query);
        $galleryExists = $db->loadObject();

        // Save
        $fields = new stdClass();
        if (isset($galleryExists->id)) {
            $fields->id = $galleryExists->id;
        }
        $fields->published = 1;
        $fields->name = $fieldsData['title'];
        $fields->alias = $galleryAlias;
        $fields->user_id = JFactory::getUser()->id;
        $fields->updated = date("Y-m-d H:i:s");
        $fields->created = date("Y-m-d H:i:s");
        $db = JFactory::getDbo();

        try {
            $query = $db->getQuery(true);
            $result = $db->insertObject($tableName, $fields);
        } catch (Exception $e) {
            $result = $db->updateObject($tableName, $fields, 'id');
        }

        if ($galleryExists) {
            return $fields->id;

        } else {
            return $db->insertid();
        }

    }

    private function saveGpxFile()
    {
        jimport('joomla.filesystem.file');
        $gpxFolder = $this->getArticleGpxFolder();
        $file = JFactory::getApplication()->input->files->get('general');
        $file = $file['gpx_file'];

        if ($file['size'] > 0) {
            JFile::copy($file['tmp_name'], JPATH_ROOT . '/' . $gpxFolder . '/' . $file['name']);
            $this->gpxFile = $gpxFolder . '/' . $file['name'];
            return true;
        }
    }

    public function validate($form, $data, $group = null)
    {


        return parent::validate($form, $data, $group);
    }

    private function saveGalleryAltTexts()
    {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            'galleryimage_alt_texts' => 'RAW',
        ));
        $altTexts = $fieldsData['galleryimage_alt_texts'];
        $db = JFactory::getDbo();

        foreach ($altTexts as $id => $altText) {
            if($altText != '') {
                $altText = 'Caption:'.$altText;
                $db->setQuery('SELECT fi.id, fi.name FROM #__fwsg_file AS fi WHERE id = ' . (int)$id);
                if ($obj = $db->loadObject()) {
                    if ($obj->name != $altText) {
                        $db->setQuery('UPDATE #__fwsg_file SET name = ' . $db->quote($altText) . ' WHERE id = ' . (int)$id);
                        $db->execute();
                    }
                }
            }
        }
    }

    private function saveGalleryOrdering()
    {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            'galleryimage_ordering' => 'RAW',
        ));
        $orders = $fieldsData['galleryimage_ordering'];
        $db = JFactory::getDbo();

        foreach ($orders as $order => $id) {

                $db->setQuery('SELECT fi.id, fi.ordering FROM #__fwsg_file AS fi WHERE id = ' . (int)$id);
                if ($obj = $db->loadObject()) {
                    $db->setQuery('UPDATE #__fwsg_file SET ordering = ' . $db->quote($order) . ' WHERE id = ' . (int)$id);
                    $db->execute();
                }
        }
    }

    private function createContent()
    {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            'general' => 'RAW',
        ));
        $fieldsDataFormed = $fieldsData['general'];

        if ($fieldsDataFormed['id'] == '0') {
            JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');
            $contentModel = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
            JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'Article');
            $contentModel = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
            $saved = $contentModel->save($fieldsDataFormed);
            $id = $contentModel->getState('article.id');
        }

        if ($saved === true) {
            $this->contentId = $id;
        } else {
            $this->contentId = $fieldsDataFormed['id'];
        }
    }

	private function deleteCache($id)
	{
		$jotcache = new MainModelMain();
		$jotrecache = new MainModelRecache();
		$db = JFactory::getDbo();
		$tableName = '#__jotcache';
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName($tableName));
		$db->setQuery($query);
		$items = $db->loadObjectList();

		foreach ($items as $key => $item) {
			$com = $item->com;
			$view = $item->view;
			$comView = "$com.$view";
//			if($comView == 'com_content.category') {
//				$idsToDelete[] = $item->fname;
//			}
			if($comView == 'com_content.article' && $item->id == $id) {
				$idsToDelete[] = $item->fname;
			}
		}
		$app = JFactory::getApplication();
		$input = $app->input;

		if (count($idsToDelete) > 0) {
			$input->set('cid', $idsToDelete);
			$jotrecache->flagRecache($idsToDelete);
			$input->set('scope', 'chck');
			$input->set('mark', '1');
			$input->set('jotcacheplugin', 'recache');

		}

		$jotrecache->runRecache();
	}


    private function saveContent($contentItemImages = null)
    {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            'general' => 'RAW',
        ));
        $fieldsDataFormed = $fieldsData['general'];
        $contentId = $this->contentId;
        $fieldsDataFormed['id'] = $contentId;
        $fieldsDataFormed['language'] = 'de-DE';

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');
        $contentModel = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
        if ($contentModel != false && $contentId > 0) {
            $item = $contentModel->getItem($contentId);
            $itemImages = $item->images;
        }

        if (isset($contentItemImages)) {
            if ($contentItemImages['image_intro']['size'] > 0 && $contentId > 0) {
                $fieldsDataFormed['images']['image_intro'] = $contentItemImages['image_intro']['final_path'];
            } else {
                $fieldsDataFormed['images']['image_intro'] = $itemImages['image_intro'];
            }

            if ($contentItemImages['image_fulltext']['size'] > 0 && $contentId > 0) {
                $fieldsDataFormed['images']['image_fulltext'] = $contentItemImages['image_fulltext']['final_path'];
            } else {
                $fieldsDataFormed['images']['image_fulltext'] = $itemImages['image_fulltext'];
            }
        }

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'Article');
        $contentModel = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
        $saved = $contentModel->save($fieldsDataFormed);

        if ($saved === true) {
	        RoadbikelifeHelper::deleteCache([
		        'com_content.article'  => [$item->id],
		        'com_content.category' => '*',
	        ],false);
	        return;
        } else {
            $error = $contentModel->getError();
            array_push($this->errors, $error);
            return $error;
        }
    }

    protected function saveFields()
    {

        $jinput = JFactory::getApplication()->input;
        $fieldsData['com_fields'] = $this->getFieldsData('com_fields');
        $article = JTable::getInstance("content");
        $article->load($this->contentId);
        isset($postedId) ? $isNew = false : $isNew = true;

        if (strtotime($fieldsData['com_fields']['birthdate']) > 0) {
            $fieldsData['com_fields']['birthdate'] = date('Y-m-d 00:00:00',
                strtotime($fieldsData['com_fields']['birthdate'])
            );
        }

        if (isset($this->galleryId) && $this->galleryFiles) {
            $fieldsData['com_fields']['gallery-id'] = $this->galleryId;
        }

        if ($this->gpxFile) {
            $fieldsData['com_fields']['gpx-file'] = $this->gpxFile;
        }

	    $result = Joomla\CMS\Factory::getApplication()->triggerEvent('onContentAfterSave', ['com_content.article', $article, $isNew, $fieldsData]);

        return $result;
    }

    private function getGalleryImages($item)
    {
        $jinput = JFactory::getApplication()->input;
        $tableName = '#__fwsg_file';
        $contentId = $jinput->getInt('id', 0);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($tableName));
        $query->where($db->quoteName('category_id') . ' = ' . $db->quote($item->{'gallery-id'}));
        $query->order($db->quoteName('ordering'));
        $db->setQuery($query);
        $galleryImages = $db->loadObjectList();
        return $galleryImages;
    }

    public function getItem($id = null)
    {
        $jinput = JFactory::getApplication()->input;
        if (!isset($id)) {
            $id = $jinput->getInt('id', '0');
        }

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');
        $contentModel = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));

        if ($contentModel != false && isset($id)) {
            $item = $contentModel->getItem($id);
        }

        if (isset($item)) {
//            $item = $this->formatItem($item);
            JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');

            // Add Content Fields
            $jcFields = FieldsHelper::getFields('com_content.article', $item, true);
            foreach ($jcFields as $field) {
                if(isset($field->rawvalue)) {
                    $item->{$field->name} = $field->rawvalue;

                }
            }

            $item->images['gallery_images'] = $this->getGalleryImages($item);


            return $item;
        } else {
            return false;
        }
    }

    public function deleteImageCache()
    {
        $id = JFactory::getApplication()->input->get('id', '0');

        $folder = JPATH_ROOT."/cache/roadbikelife-images/images/content/$id";

        if (file_exists($folder)) {
            jimport('joomla.filesystem.folder');
            JFolder::delete($folder);
        }

        $folder = JPATH_ROOT."administrator/cache/roadbikelife-images/content/$id";

        if (file_exists($folder)) {
            jimport('joomla.filesystem.folder');
            JFolder::delete($folder);
        }

        echo "<script>window.close();</script>";

//        JFactory::getApplication()->redirect(
//            JRoute::_('index.php?option=com_roadbikelife&view=createcontent&id=' . $id),
//            'Image Cache erfolgreich gelöscht'
//        );

    }

    public function getForm($item)
    {
        $jinput = JFactory::getApplication()->input;
        $form = JForm::getInstance('com_content.article', JPATH_COMPONENT_ADMINISTRATOR . '/models/forms/createcontent.xml');
//        $form = $this->loadForm('com_content.article', 'article', array('control' => 'jform', 'load_data' => $item));
        $data = $item;
//	    $event     = new Joomla\Application\Event\ApplicationEvent('onContentPrepareForm', Factory::getApplication());
	    $dispatcher = Joomla\CMS\Factory::getApplication()->getDispatcher();
	    $event = new Event('onContentPrepareForm', [$form, $data]);
	    $dispatcher->dispatch('onContentPrepareForm',$event);
//	    Factory::getApplication()->triggerEvent('onContentPrepareForm', array($form, $data));

	    if (empty($form)) {
            return false;
        }

        foreach ($form->getFieldsets() as $fieldset) {
            $fields = $form->getFieldset($fieldset->name);
            if (count($fields)) {
                foreach ($fields as &$field) {
                    if (isset($item)) {
                        $value = $data->{$field->fieldname};
                    }

                    if ($field->type == 'calendar') {
                        $value = JHtml::date($value, 'd.m.YYYY');
                    }

                    $form->setValue($field->fieldname, $field->group, $value);
                }
            }
        }


        return $form;
    }

//    protected function redirectAfterSave($contentItemSavedId)
//    {
//        if (!isset($contentItemSavedId)) {
//            $app = JFactory::getApplication();
//            $link = JURI::base() . 'index.php?option=com_roadbikelife&view=createcontent';
//            $msg = 'Beim speichern ist ein Fehler aufgetreten';
//            $app->redirect(Juri::current(), $msg, $msgType = 'warning');
//            return false;
//        }
//
//        $app = JFactory::getApplication();
//        $jinput = JFactory::getApplication()->input;
//        $postedId = $jinput->get('id', null, 'int');
//        isset($postedId) ? $isNew = false : $isNew = true;
//
//        if (count($this->errors) < 1) {
//            $msg = 'Der Fragebogen wurde erfolgreich gespeichert!';
//            $msgType = 'success';
//        }
//
//        $app->redirect(Juri::base() . 'index.php?option=com_roadbikelife&view=createcontent&id=' . $contentItemSavedId, $msg, $msgType);
//
//    }

    protected function queueErrors()
    {
        if (count($this->errors) < 1) {
            return false;
        } else {
            foreach ($this->errors as $error) {
                JFactory::getApplication()->enqueueMessage($error, 'danger');
            }
            return true;
        }
    }
}
