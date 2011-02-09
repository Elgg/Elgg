<?php
/**
 * This displays a plugin's user settings.
 *
 * @package Elgg.Plugin
 * @subpackage Settings
 */


$plugin = $vars['plugin'];
$plugin_id = $plugin->getID();
$user_guid = $details['user_guid'];
if (!$user_guid) {
	$user_guid = elgg_get_logged_in_user_guid();
}

if (elgg_view("usersettings/$plugin_id/edit")) {
?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo $plugin->manifest->getName(); ?></h3>
	</div>
	<div class="elgg-body">
		<div id="<?php echo $plugin; ?>_settings">
			<?php echo elgg_view("object/plugin", array(
				'plugin' => $plugin,
				'entity' => find_plugin_usersettings($plugin_id, $user_guid),
				'prefix' => 'user'
			));
			?>
		</div>
	</div>
</div>
<?php
}