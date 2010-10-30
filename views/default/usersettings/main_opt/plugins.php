<?php
/**
 * Elgg plugin sub-component on the main menu.
 *
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;
?>
<div class="menu_admin_option">
	<h2><?php echo elgg_echo('usersettings:plugins'); ?> </h2>
	<p><?php echo elgg_echo('usersettings:plugins:opt:description'); ?><br />
	<a href="<?php echo elgg_get_site_url() . "pg/settings/plugins/"; ?>"><?php echo elgg_echo('usersettings:plugins:opt:linktext'); ?></a></p>
</div>