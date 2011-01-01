<?php
/**
 * Elgg plugin manifest class
 *
 * This file renders a plugin for the admin screen, including active/deactive, manifest details & display plugin
 * settings.
 *
 * @package Elgg
 * @subpackage Core
 */


$plugin = $vars['plugin'];
$details = $vars['details'];

$active = $details['active'];
$manifest = $details['manifest'];

$user_guid = $details['user_guid'];
if ($user_guid) {
	$user_guid = get_loggedin_userid();
}

if (elgg_view("usersettings/{$plugin}/edit")) {
?>
<div class="elgg-module elgg-info-module">
	<div class="elgg-head">
		<h3><?php echo elgg_echo($plugin); ?></h3>
	</div>
	<div class="elgg-body">
		<div id="<?php echo $plugin; ?>_settings">
			<?php echo elgg_view("object/plugin", array(
				'plugin' => $plugin,
				'entity' => find_plugin_usersettings($plugin, $user_guid),
				'prefix' => 'user'
			));
			?>
		</div>
	</div>
</div>	
<?php
}