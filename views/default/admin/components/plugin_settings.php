<?php
/**
 * Elgg plugin settings
 *
 * @uses ElggPlugin $vars['plugin'] The plugin object to display settings for.
 *
 * @package Elgg.Core
 * @subpackage Plugins.Settings
 */

$plugin = $vars['plugin'];
$plugin_id = $plugin->getID();

if (elgg_view_exists("settings/$plugin_id/edit")) {
?>

<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo $plugin->manifest->getName(); ?></h3>
	</div>
	<div class="elgg-body">
		<?php
			$params = array('internalid' => "$plugin_id-settings");
			echo elgg_view_form("plugins/settings/save", $params, $vars);
		?>
	</div>
</div>
<?php
}