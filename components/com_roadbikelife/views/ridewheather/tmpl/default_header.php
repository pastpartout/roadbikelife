<?php

defined('_JEXEC') or die;

$task = JFactory::getApplication()->input->getString('task', '');
?>
<header>
    <div class="row no-gutters">
        <div class="col-md-4 colLeft">
            <?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header_logo.php' ?>
        </div>
        <?php if ($task != 'login'): ?>
            <div class="col-md-8 colRight">
                <div class="row no-gutters">
                    <div class="col colTitle flex-grow-1">
                        <h1 class="h5 mb-0">
                            <?= $headerTitle; ?>
                            <?php if ($task == 'list'): ?>
                                <span class="badge badge-light"><?= count($this->items) ?></span>
                            <?php endif ?>
                        </h1>
                    </div>
                    <?php require JPATH_BASE . '/components/com_roadbikelife/views/ridewheather/tmpl/default_header_buttons.php' ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</header>
