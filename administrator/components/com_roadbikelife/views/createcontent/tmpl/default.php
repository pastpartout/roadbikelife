<?php
defined('_JEXEC') or die;
require_once JPATH_ROOT . "/vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";
$extraFieldsName = 'com_fields';
$customFieldsDetails = $this->form->getFieldset('fields-0');
$version = '?v3';
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');

$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . 'components/com_roadbikelife/assets/js/jquery.imageScroller.min.js' . $version);
$doc->addScript(JUri::base() . 'components/com_roadbikelife/views/createcontent/tmpl/js/jquery.ui.core.min.js');
$doc->addScript(JUri::base() . 'components/com_roadbikelife/views/createcontent/tmpl/js/jquery-sortable.js');
$doc->addScript(JUri::base() . 'components/com_roadbikelife/assets/js/createcontent.js' . $version);
$doc->addStyleSheet(JUri::base() . 'components/com_roadbikelife/assets/css/roadbikelife.css' . $version);
$doc->addStyleSheet(JUri::base() . 'components/com_roadbikelife/assets/css/image-scroller.css' . $version);

JLoader::register('RoadbikelifeModelResizeimage', JPATH_ROOT . '/components/com_roadbikelife/models/resizeimage.php');
$imageModel = new RoadbikelifeModelResizeimage();
JFactory::getDocument()->addScriptDeclaration("
		Joomla.submitbutton = function(task)
		{
			var form = document.getElementById('adminForm');
			if (task == 'menu.cancel' || document.formvalidator.isValid(form))
			{
				Joomla.submitform(task, form);
			}
		};
");

$detect = new Mobile_Detect;
if ($detect->isMobile() && !$detect->isTablet()) {
    $isSmartphone = true;
} else {
    $isSmartphone = false;
}


?>

<form class="edit-form main-card text-body form-validate" name="adminForm" method="POST" id="adminForm"
      enctype="multipart/form-data">

    <div class="container-fluid container-main" id="com_roadbikelife">
        <div id="com_roadbikelife" class="index">
            <?php echo JHtml::_('uitab.startTabSet', 'myTab', array('active' => 'general')); ?>
            <div class="hide">
                <input type="hidden" name="task" value="">
                <?= $this->form->getGroup('general')['general_id']->renderField(); ?>
                <?= $this->form->getGroup('general')['general_catid']->renderField(); ?>
            </div>
            <?php echo JHtml::_('uitab.addTab', 'myTab', 'general', 'Allgemein'); ?>
            <div class="row">
                <div class="col-lg-9">
                    <?= $this->form->getGroup('general')['general_title']->renderField(); ?>
                    <?= $this->form->getGroup($extraFieldsName)['com_fields_subheadline']->renderField(); ?>
                    <?= $this->form->getGroup('general')['general_articletext']->renderField(); ?>
                    <hr class="clear">
                    <h4>Contentpluginvorlagen</h4>
                    <p>Bild im Content:</p>
                    <pre>{contentimage images/content/42/123.jpg|Caption|sm-right}</pre>
                    <p>Link zu Gallereibild im Content:</p>
                    <pre>{contentimagelink 4|Die "Kinderecke"|sm-right} </pre>
                    <hr>
                    <a class="btn btn-primary" href="<?= JRoute::_('index.php?option=com_roadbikelife&task=createcontent.deleteimagecache&id=' . $this->item->id); ?>" target="_blank">
                        Bildercache leeren
                    </a>
                    <?php if(isset($this->item->id)):?>
                        <a href="<?= JURI::root().'component/roadbikelife/apiupdate/runstrava/'.RoadbikelifeHelper::getParam('urlToken')?>/<?= $this->item->id?>"
                           target="_blank" class="btn btn-primary">
                            Strava Update starten
                        </a>
                    <?php endif ?>
                </div>
                <div class="col-lg-3">
                    <?= $this->form->getGroup($extraFieldsName)['com_fields_stravaactivityid']->renderField(); ?>
<!--                    --><?php //= $this->form->getGroup($extraFieldsName)['com_fields_beitrag_verstecken']->renderField(); ?>
                    <?php foreach ($this->form->getFieldset('general_sidebar') as $field) : ?>
                        <?php echo $field->renderField(); ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php echo JHtml::_('uitab.endTab'); ?>
            <?php echo JHtml::_('uitab.addTab', 'myTab', 'images', 'Fotos'); ?>

            <h4>Bilder</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="control-group">
                        <div class="control-label">
                            <label id="images_image_intro-lbl" for="images_image_intro">
                                Volltext Bild</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
				            <?= $this->form->getGroup($extraFieldsName)['com_fields_header_img_alt']->renderField(); ?>
                        </div>

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="control-group">
                        <div class="control-label"><label id="images_image_intro-lbl" for="images_image_intro">
                                Intro Bild</label>
                        </div>
                        <div class="controls">
                            <?php if ($this->item->images['image_intro'] != ''): ?>
                                <div class="controls thumbnail">
                                    <img src="<?= JURI::base().'../' . $this->item->images['image_intro'] ?>" class="img-fluid"/>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (count($this->item->images['gallery_images'])): ?>
                <h4>Galleriebilder</h4>

                <div class="control-group">
                    <div class="controls">
                        <ul class="gallery manager thumbnails">
                            <?php foreach ($this->item->images['gallery_images'] as $key => $image): ?>
                                <li class="imgOutline thumbnail center">

                                    <div class="imgThumb imgInput">
                                        <img
                                                class="img-fluid img-thumbnail"
                                                src="<?php echo JURI::root() . "index.php?option=com_fwgallery&view=item&layout=img&format=raw&w=300&h=300&id=" . urlencode($image->id); ?>"
                                                alt="<?php echo htmlspecialchars($image->name, ENT_COMPAT, 'UTF-8'); ?>"
                                                itemprop="thumbnailUrl"/>
                                    </div>
                                    <div class="imgPreview nowrap small">
                                        <p>
                                            <?= $image->name ?>
                                        </p>
                                        <div>
                                            <input type="text" placeholder="Bildunterschrift"
                                                   name="galleryimage_alt_texts[<?= $image->id ?>]"
                                                   class="form-control form-control-sm"/>
                                            <input type="hidden" placeholder="Bildunterschrift"
                                                   name="galleryimage_ordering[]" value="<?= $image->id ?>"
                                                   class="form-control form-control-sm"/>
                                        </div>
                                        <?php if ($detect->isMobile()): ?>
                                            <div class="btn-group" style="padding-top: 5px">
                                                <a class="btn btn-micro active down">
                                                    <span class="caret"></span>
                                                </a>
                                                <a class="btn btn-micro active up">
                                                    <span class="caret" style="transform: rotate(180deg)"></span>
                                                </a>

                                            </div>

                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif ?>
            <hr>
            <div class="row">
                <?php foreach ($this->form->getFieldset('images') as $field) : ?>
                    <div class="col-md-3">
                        <?php echo $field->renderField(); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php echo JHtml::_('uitab.endTab'); ?>
            <?php echo JHtml::_('uitab.endTabSet'); ?>
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>
