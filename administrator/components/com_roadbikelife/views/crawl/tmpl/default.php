<?php
/**
 * @version    4.3.1
 * @package    com_applicationform
 * @author     Glenn Arkell <glenn@glennarkell.com.au>
 * @copyright  2020 Glenn Arkell
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


use \Joomla\CMS\HTML\HTMLHelper;
use \GlennArkell\Component\Gavoting\Administrator\Helper\GavotingHelper;

JHtml::_('jquery.framework');
HTMLHelper::_('behavior.core');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('behavior.formvalidator');

$wa  = $this->document->getWebAssetManager();
$wa->registerAndUseScript('crawl', 'administrator/components/com_roadbikelife/assets/js/crawl.js');
?>

<form class="edit-form text-body" name="adminForm" id="adminForm" method="POST" enctype="multipart/form-data">
    <div id="results"></div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>