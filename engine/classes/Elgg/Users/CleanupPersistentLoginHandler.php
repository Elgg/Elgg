<?php

namespace Elgg\Users;

/**
 * Cleans persistent login tokens
 *
 * @since 4.0
 */
class CleanupPersistentLoginHandler {
	
	/**
	 * Cleanup expired persistent login tokens from the database
	 *
	 * @param \Elgg\Hook $hook 'cron', 'daily'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$time = (int) $hook->getParam('time', time());
		_elgg_services()->persistentLogin->removeExpiredTokens($time);
	}
}
