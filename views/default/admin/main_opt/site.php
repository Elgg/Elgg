<?php
	/**
	 * Elgg site sub-component on the main menu.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
?>
<div class="admin-menu-option">
	<h2><?php echo elgg_echo('admin:site'); ?> </h2>
	<p><?php echo elgg_echo('admin:site:opt:description'); ?><br />
	<a href="<?php echo $CONFIG->wwwroot . "pg/admin/site/"; ?>"><?php echo elgg_echo('admin:site:opt:linktext'); ?></a></p>
</div>