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
	 * @param \Elgg\Event $event 'cron', 'daily'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$time = (int) $event->getParam('time', time());
		_elgg_services()->persistentLogin->removeExpiredTokens($time);
	}
}
