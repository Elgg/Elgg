<?php
/**
 * Changes the load order of a plugin.
 *
 * Plugin order affects priority for view, action, and page handler
 * overriding as well as the order of view extensions.  Higher numbers
 * are loaded after lower numbers, and so receive higher priority.
 *
 * NOTE: When viewing the admin page (advanced plugin admin in >= 1.8) plugins
 * LOWER on the page have HIGHER priority and will override views, etc
 * from plugins above them.
 *
 * @package Elgg.Core
 * @subpackage Administration.Site
 */

$mod = get_input('plugin');
$mod = str_replace('.', '', $mod);
$mod = str_replace('/', '', $mod);

// Get the new order
$order = (int) get_input('order');

// Get the current plugin list
$plugins = get_plugin_list();

// Inject the plugin order back into the list
if ($key = array_search($mod, $plugins)) {

	unset($plugins[$key]);
	while (isset($plugins[$order])) {
		$order++;
	}

	$plugins[$order] = $mod;
}

// Disable
if (regenerate_plugin_list($plugins)) {
	elgg_delete_admin_notice('first_installation_plugin_reminder');
	system_message(elgg_echo('admin:plugins:reorder:yes', array($plugin)));
} else {
	register_error(elgg_echo('admin:plugins:reorder:no', array($plugin)));
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);