<?php

$modules = [];
foreach (elgg_get_plugins() as $plugin) {
	$id = $plugin->getID();
	if (elgg_view_exists("boot/$id.js")) {
		$modules[] = "boot/$id";
	}
}

?>
//<script>
/**
 * Finalize the boot sequence by making sure all available $plugin_id/boot modules are
 * loaded before firing the init/ready plugin hooks.
 */
define(function (require) {
	var Plugin = require('elgg/Plugin');
	var elgg = require('elgg');
	var modules = [];
	var i;

	<?php foreach ($modules as $module) { ?>
	modules.push({plugin: require('<?php echo $module ?>'), name: '<?php echo $module ?>'});
	<?php } ?>

	for (i = 0; i < modules.length; i++) {
		if (modules[i].plugin instanceof Plugin) {
			modules[i].plugin._init();
		} else {
			console.error("Boot module " + modules[i].name + " did not return an instance of Plugin (from elgg/Plugin)");
		}
	}

	elgg.trigger_hook('init', 'system');
	elgg.trigger_hook('ready', 'system');
});
