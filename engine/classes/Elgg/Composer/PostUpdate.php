<?php

namespace Elgg\Composer;

use Composer\Script\Event;
use Elgg;
use Elgg\Filesystem\Directory;

/**
 * A composer command handler to run after composer updates (and installs)
 */
class PostUpdate {
	/**
	 * Copies files that Elgg expects to be in the root directory.
	 * 
	 * @param Event $event The Composer event (install/upgrade)
	 * 
	 * @return void
	 */
	public static function execute(Event $event) {
		self::copyFileToRoot("install/config/htaccess.dist", ".htaccess");
		self::copyFileToRoot("index.php", "index.php");
		self::copyFileToRoot("install.php", "install.php");
		self::copyFileToRoot("upgrade.php", "upgrade.php");
		
		$managed_plugins = array(
			'aalborg_theme',
			'blog',
			'bookmarks',
			'categories',
			'ckeditor',
			'custom_index',
			'dashboard',
			'developers',
			'diagnostics',
			'discussions',
			'embed',
			'externalpages',
			'file',
			'garbagecollector',
			'groups',
			'htmlawed',
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
			'twitter_api',
			'uservalidationbyemail',
			'web_services',
			'zaudio',
		);
		
		foreach ($managed_plugins as $id) {
			self::copyPluginToRoot($id);
		}
	}
	
	/**
	 * Copies a file from the given location in Elgg to the given location in root.
	 * 
	 * @param string $elggPath Path relative to elgg dir.
	 * @param string $rootPath Path relative to app root dir.
	 * 
	 * @return void
	 */
	private static function copyFileToRoot($elggPath, $rootPath) {
		$from = Elgg\Application::elggDir()->getPath($elggPath);
		$to = Directory\Local::root()->getPath($rootPath);
		
		if ($from == $to) {
			return;
		}
		
		echo "Copying '$from' to '$to'...\n";
		copy($from, $to);
	}
	
	/**
	 * Move a plugin from Elgg core to application root
	 */
	private static function copyPluginToRoot($id) {
		$from = Elgg\Application::elggDir()->getPath("mod/$id");
		$to = Directory\Local::root()->getPath("mod/$id");
		
		if ($from == $to) {
			return;
		}
		
		echo "Moving plugin '$id' from '$from' to '$to'...";
		rename($from, $to);
	}
}
