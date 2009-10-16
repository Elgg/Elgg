<?php
/**
 * Elgg user sub-component on the main menu.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>
<div class="admin-menu-option">
	<h2><?php echo elgg_echo('usersettings:user'); ?> </h2>
	<p><?php echo elgg_echo('usersettings:user:opt:description'); ?><br />
	<a href="<?php echo $CONFIG->wwwroot . "pg/settings/user/"; ?>"><?php echo elgg_echo('usersettings:user:opt:linktext'); ?></a></p>
</div>