<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the search functions only once
JLoader::register('ModSearchHelper', __DIR__ . '/helper.php');

$lang       = JFactory::getLanguage();
$app        = JFactory::getApplication();
$set_Itemid = (int) $params->get('set_itemid', 0);
$mitemid    = $set_Itemid > 0 ? $set_Itemid : $app->input->getInt('Itemid');

?>
<div class="search">
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" role="search">
        <div class="input-group w-100 mb-3">
            <input type="text" name="searchword" title="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>"
                   placeholder="<?php echo JText::_('SEARCH_KEYWORD_PLACEHOLDER'); ?>"
                   class="form-control"/>
            <div class="input-group-append">
                <button name="Search" onclick="this.form.submit()" class="btn btn-secondary rounded-right"
                        title="<?php echo JHtml::_('tooltipText', 'COM_SEARCH_SEARCH'); ?>">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
	</form>
</div>
