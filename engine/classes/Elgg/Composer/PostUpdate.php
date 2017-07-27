<?php
namespace Elgg\Composer;

use Composer\Installer\PackageEvent;

/**
 * A composer command handler to run after post-package-update event
 */
class PostUpdate {
	/**
	 * Todo
	 *
	 * @param PackageEvent $event The Composer event (install/upgrade)
	 *
	 * @return void
	 */
	public static function execute(PackageEvent $event) {

	}
}
