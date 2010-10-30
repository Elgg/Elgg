<?php
/**
 * Elgg user sub-component on the main menu.
 *
 * @package Elgg
 * @subpackage Core
 */
?>
<div class="menu_admin_option">
	<h2><?php echo elgg_echo('usersettings:user'); ?> </h2>
	<p><?php echo elgg_echo('usersettings:user:opt:description'); ?><br />
	<a href="<?php echo elgg_get_site_url() . "pg/settings/user/"; ?>"><?php echo elgg_echo('usersettings:user:opt:linktext'); ?></a></p>
</div>