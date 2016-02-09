<?php
/**
 * Boot all the plugins and trigger the [init, system] hook
 *
 * Depend on this module to guarantee all [init, system] handlers have been called
 */

$modules = [];
foreach (elgg_get_plugins() as $plugin) {
	$id = $plugin->getID();
	if (elgg_view_exists("boot/$id.js")) {
		$modules[] = "boot/$id";
	}
}

?>
//<script>
define(function (require) {
	var Plugin = require('elgg/Plugin');
	var elgg = require('elgg');

	var modules = [];
	var i;

	// We need literal require('boot/example'); statements. We can't use async require in here because we promise to
	// not return this module until all boot plugins are loaded.
<?php foreach ($modules as $name) { ?>
	modules.push({
		plugin: require(<?= json_encode($name, JSON_UNESCAPED_SLASHES) ?>),
		name: <?= json_encode($name, JSON_UNESCAPED_SLASHES) ?>
	});
<?php } ?>

	for (i = 0; i < modules.length; i++) {
		if (modules[i].plugin instanceof Plugin) {
			modules[i].plugin._init();
		} else {
			console.error("Boot module boot/" + modules[i].name + " did not return an instance of Plugin (from elgg/Plugin)");
		}
	}

	elgg.trigger_hook('init', 'system');
});
