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

if (elgg_view("settings/$plugin_id/edit")) {
?>

<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo $plugin->manifest->getName(); ?></h3>
	</div>
	<div class="elgg-body">
		<div id="<?php echo $plugin_id; ?>_settings">
			<?php echo elgg_view("object/plugin", array(
				'plugin' => $plugin,
				// in for backward compatibility
				'entity' => $plugin,
				'type' => 'admin'
			));
			?>
		</div>
	</div>
</div>
<?php
}