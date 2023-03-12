<?php

defined('_JEXEC') or die;

$config = JFactory::getConfig();

?>
<div class="d-flex align-items-center" href="<?php echo Juri::base() ?>">
    <a href="<?php echo Juri::base() ?>">
        <img src="<?php echo JURI::base() ?>/templates/custom/img/logo_white.svg?v2"
             class="logo img-fluid"
             alt="Logo des <?= $config->get('sitename'); ?> Blogs">
    </a>
    <a class="small mt-n1" href="<?= JRoute::_('index.php?option=com_roadbikelife&view=ridewheather&task=create') ?>" target="_blank" class="poweredByRideWheather">

        <div class="badge badge-sm  ml-2 bg-white">
            <span class="font-weight-bold mb-0">
                RideWheather<i class="fa fa-sun ml-1"></i>
            </span>
        </div>
    </a>
</div>
