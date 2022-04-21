<?php
/**
 * Enables all core plugins
 */

use Elgg\Exceptions\PluginException;

$root = dirname(dirname(__DIR__));
require_once "$root/autoloader.php";

\Elgg\Application::start();

_elgg_services()->plugins->generateEntities();

$ordered_plugins = [
	'activity',
	'blog',
	'bookmarks',
	'ckeditor',
	'dashboard',
	'developers',
	'discussions',
	'externalpages',
	'file',
	'friends',
	'friends_collections',
	'garbagecollector',
	'groups',
	'invitefriends',
	'likes',
	'members',
	'messageboard',
	'messages',
	'pages',
	'profile',
	'reportedcontent',
	'search',
	'site_notifications',
	'system_log',
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
	
	if (!$plugin->isActive()) {
		try {
			$plugin->activate();
		} catch (PluginException $e) {
			echo "Unable to activate plugin {$plugin_id}";
			exit(1);
		}
	}
}

echo "The following plugins are active" . PHP_EOL;

$plugins = elgg_get_plugins('active');
foreach($plugins as $plugin) {
	echo $plugin->getPriority() . " -> " . $plugin->getID() . PHP_EOL;
}
