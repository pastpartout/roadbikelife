<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

$doc = JFactory::getDocument();
$doc->addScript('templates/custom/js/jquery.validate.min.js');
$doc->addScript('media/media/js/localization/messages_de.js');
$doc->addScript('media/media/js/localization/methods_de.js');
?>
<div class="reset<?php echo $this->pageclass_sfx; ?> container">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<form id="user-registration"
          action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>"
          method="post" class="form-validate form-horizontal well">
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<fieldset>
				<p><?php echo JText::_($fieldset->label); ?></p>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<?php if ($field->hidden === false) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</fieldset>
		<?php endforeach; ?>
		<div class="control-group submit-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary validate">
					<?php echo JText::_('JSUBMIT'); ?>
				</button>
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<script>
    $('form.form-validate').validate({
        submitHandler: function(form) {
            if (grecaptcha.getResponse()) {
                form.submit();
            } else {
                alert('Please confirm captcha to proceed')
            }
        }
    });
</script>
