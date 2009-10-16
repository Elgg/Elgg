<?php
/**
 * Elgg plugin sub-component on the main menu.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;
?>
<div class="admin-menu-option">
	<h2><?php echo elgg_echo('usersettings:plugins'); ?> </h2>
	<p><?php echo elgg_echo('usersettings:plugins:opt:description'); ?><br />
	<a href="<?php echo $CONFIG->wwwroot . "pg/settings/plugins/"; ?>"><?php echo elgg_echo('usersettings:plugins:opt:linktext'); ?></a></p>
</div>