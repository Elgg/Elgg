<?php
/**
 * Changes the load priority of a plugin.
 *
 * Plugin priority affects view, action, and page handler
 * overriding as well as the order of view extensions.  Plugins with higher
 * priority are loaded after and override plugins with lower priorities.
 *
 * NOTE: When viewing the admin page (advanced plugin admin in >= 1.8) plugins
 * LOWER on the page have HIGHER priority and will override views, etc
 * from plugins above them.
 *
 * @package Elgg.Core
 * @subpackage Administration.Plugins
 */

$plugin_guid = get_input('plugin_guid');
$priority = get_input('priority');

$plugin = get_entity($plugin_guid);

if (!($plugin instanceof ElggPlugin)) {
	register_error(elgg_echo('admin:plugins:set_priority:no', array($plugin_guid)));
	forward(REFERER);
}

if ($plugin->setPriority($priority)) {
	//system_message(elgg_echo('admin:plugins:set_priority:yes', array($plugin->getManifest()->getName())));
} else {
	register_error(elgg_echo('admin:plugins:set_priority:no', array($plugin->getManifest()->getName())));
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);