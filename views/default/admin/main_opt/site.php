<?php
/**
 * Elgg site sub-component on the main menu.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>
<div class="admin-menu-option">
	<h2><?php echo elgg_echo('admin:site'); ?> </h2>
	<p><?php echo elgg_echo('admin:site:opt:description'); ?><br />
	<a href="<?php echo $CONFIG->wwwroot . "pg/admin/site/"; ?>"><?php echo elgg_echo('admin:site:opt:linktext'); ?></a></p>
</div>