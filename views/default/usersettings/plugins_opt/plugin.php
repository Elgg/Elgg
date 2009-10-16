<?php
/**
 * Elgg plugin manifest class
 *
 * This file renders a plugin for the admin screen, including active/deactive, manifest details & display plugin
 * settings.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */


$plugin = $vars['plugin'];
$details = $vars['details'];

$active = $details['active'];
$manifest = $details['manifest'];

$user_guid = $details['user_guid'];
if ($user_guid) {
	$user_guid = $_SESSION['user']->guid;
}

if (elgg_view("usersettings/{$plugin}/edit")) {
?>
<div class="contentWrapper">
	<h3 class="settings"><?php echo elgg_echo($plugin); ?></h3>

	<div id="<?php echo $plugin; ?>_settings">
		<?php echo elgg_view("object/plugin", array('plugin' => $plugin, 'entity' => find_plugin_usersettings($plugin, $user_guid), 'prefix' => 'user')) ?>
	</div>
</div>
<?php
}