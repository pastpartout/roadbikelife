<?php
defined('_JEXEC') or die;

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


?>

<form class="edit-form text-body" name="adminForm" method="POST" enctype="multipart/form-data">
    <div class="container-fluid container-main" id="com_roadbikelife">
        <div class="well">
            <h3 class="rblHeadline">Optionen</h3>
            <hr>
            <div class="row">
                <?php foreach ($this->form->getFieldset('general_main') as $field) : ?>
                    <div class="col-md-6">
                        <?php echo $field->renderField(); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn">OK</button>

        </div>
    </div>
    <div class="container-fluid container-main" id="com_roadbikelife">
        <h3><?= $this->visits ?> Besucher</h3>
        <div id="visitChart" class="graph"></div>
        <div class="row">
            <div class="col-md-6">
                <h3>Browser</h3>
                <div id="browserChart" class="pieChart graph">
                </div>
            </div>
            <div class="col-md-6">
                <h3>Plattformen</h3>
                <div id="deviceChart" class="pieChart graph"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Länder</h3>
                <div id="countryChart" class="pieChart graph"></div>
            </div>
            <div class="col-md-6">
                <h3>Städte</h3>
                <div id="cityChart" class="pieChart graph"></div>
            </div>
        </div>
    </div>

</form>
