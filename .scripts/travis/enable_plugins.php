<?php

/**
 * Enables all core plugins
 */

$root = dirname(dirname(__DIR__));
require_once "$root/autoloader.php";

\Elgg\Application::start();

$ordered_plugins = [
	'activity',
	'blog',
	'bookmarks',
	'ckeditor',
	'dashboard',
	'developers',
	'diagnostics',
	'discussions',
	'externalpages',
	'file',
	'friends',
	'friends_collections',
	'garbagecollector',
	'groups',
	'invitefriends',
	'legacy_urls',
	'likes',
	'logbrowser',
	'logrotate',
	'members',
	'messageboard',
	'messages',
	'notifications',
	'pages',
	'profile',
	'reportedcontent',
	'search',
	'site_notifications',
	'tagcloud',
	'thewire',
	'uservalidationbyemail',
	'web_services',
	
	// these plugins need to be activated after a previous activated plugin
	'custom_index',
	'embed',
];

foreach ($ordered_plugins as $priority => $plugin_id) {
	$plugin = elgg_get_plugin_from_id($plugin_id);
	if (empty($plugin)) {
		echo "Could not find plugin {$plugin_id} to activate";
		exit(1);
	}
	
	// set correct position
	if (!$plugin->setPriority($priority + 1)) {
		echo "Could not set priority for plugin {$plugin_id}";
		exit(1);
	}
	
	if (!$plugin->isActive() && !$plugin->activate()) {
		echo "Unable to activate plugin {$plugin_id}";
		exit(1);
	}
}
