<?php

$modules = [];
foreach (elgg_get_plugins() as $plugin) {
	$id = $plugin->getID();
	if (elgg_view_exists("boot/$id.js")) {
		$modules[] = [
			'name' => "boot/$id",
			'id' => $id,
		];
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
	var id_map = {};
	var i, module;

	<?php foreach ($modules as $module) { ?>
	module = {
		plugin: require(<?= json_encode($module['name']) ?>),
		name: <?= json_encode($module['name']) ?>,
		exports: null
	};
	modules.push(module);
	id_map[<?= json_encode($module['id']) ?>] = module;
	<?php } ?>

	for (i = 0; i < modules.length; i++) {
		if (modules[i].plugin instanceof Plugin) {
			modules[i].plugin._init();

		} else {
			console.error("Boot module " + modules[i].name + " did not return an instance of Plugin (from elgg/Plugin)");
		}
	}

	// will check in elgg/hooks/register
	elgg._plugins_booted = true;

	elgg._trigger_hook('init', 'system');
	elgg._trigger_hook('ready', 'system');

	return {
		/**
		 * Get a booted Plugin object (null if plugin not present or doesn't have a boot module)
		 *
		 * @param {String} id Plugin ID
		 * @returns {elgg/Plugin|null}
		 */
		get_plugin: function(id) {
			return id_map.hasOwnProperty(id) ? id_map[id] : null;
		}
	};
});
