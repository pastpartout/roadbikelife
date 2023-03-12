<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$config = JFactory::getConfig();
// Note. It is important to remove spaces between elements.
?>
<?php // The menu class is deprecated. Use nav instead. ?>
<header>
    <nav class="navbar navbar-expand-lg <?php echo $class_sfx; ?>">
            <a class="navbar-brand" href="<?php echo Juri::base() ?>">
                <img src="<?php echo JURI::base() ?>/templates/custom/img/logo_white.svg?v2"
                     class="logo img-fluid"
                     alt="Logo des <?= $config->get( 'sitename' ); ?> Blogs">
            </a>
    </nav>
</header>
