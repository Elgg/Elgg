<?php

/**
 * Enables all core plugins
 */

$root = dirname(dirname(__DIR__));
require_once "$root/autoloader.php";

\Elgg\Application::start();

$managed_plugins = [
	'activity',
	'blog',
	'bookmarks',
	'ckeditor',
	'custom_index',
	'dashboard',
	'developers',
	'diagnostics',
	'discussions',
	'embed',
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
];

$plugins = _elgg_services()->plugins->find('all');
foreach ($plugins as $plugin) {
	if (!in_array($plugin->getID(), $managed_plugins)) {
		$plugin->deactivate();
	} else {
		$plugin->activate();
	}
}