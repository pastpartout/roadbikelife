<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<?php if ($this->error) : ?>
	<div class="alert alert-warning text-center error">
		<?php echo $this->escape($this->error); ?>
	</div>
<?php endif; ?>
