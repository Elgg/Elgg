<?php

$modules = [];
foreach (elgg_get_plugins() as $plugin) {
	$id = $plugin->getID();
	if (elgg_view_exists("js/$id/boot.js")) {
		$modules[] = "$id/boot";
	}
}

?>
//<script>
/**
 * Finalize the boot sequence by making sure all available $plugin_id/boot modules are
 * loaded (hooks and behaviors registered) before firing the init/ready plugin hooks and
 * attaching behaviors in the DOM.
 */
define(function (require) {
	var behaviors = require('elgg/behaviors');
	var elgg = require('elgg');
	var modules = [];
	var i;

	<?php foreach ($modules as $module) { ?>
	modules.push(require('<?php echo $module ?>'));
	<?php } ?>

	for (i = 0; i < modules.length; i++) {
		if (modules[i]) {
			if (modules[i].addBehavior) {
				behaviors.addAttacher(modules[i].addBehavior);
			}
		}
	}

	elgg.trigger_hook('init', 'system');
	elgg.trigger_hook('ready', 'system');

	behaviors.attach(document.body);
});
