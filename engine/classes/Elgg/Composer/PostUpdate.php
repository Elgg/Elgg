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
		self::copyFromElggToRoot("install/config/htaccess.dist", ".htaccess");
		self::copyFromElggToRoot("index.php", "index.php");
		self::copyFromElggToRoot("install.php", "install.php");
		self::copyFromElggToRoot("upgrade.php", "upgrade.php");
	}
	
	/**
	 * Copies a file from the given location in Elgg to the given location in root.
	 * 
	 * @param string $elggPath Path relative to elgg dir.
	 * @param string $rootPath Path relative to app root dir.
	 * 
	 * @return void
	 */
	private static function copyFromElggToRoot($elggPath, $rootPath) {
		$from = Elgg\Application::elggDir()->getPath($elggPath);
		$to = Directory\Local::root()->getPath($rootPath);
		
		echo "Copying '$from' to '$to'...\n";
		copy($from, $to);
	}
}
