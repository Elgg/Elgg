<?php

namespace Elgg\Upgrade;

/**
 * Removes admin notices
 *
 * @since 4.0
 */
class UpgradeCompletedAdminNoticeHandler {
	
	/**
	 * Perform some clean up when upgrade completes
	 *
	 * @param \Elgg\Event $event 'complete', 'upgrade'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$pending = _elgg_services()->upgrades->getPendingUpgrades();
		if (empty($pending)) {
			elgg_delete_admin_notice('pending_upgrades');
		}
	}
}
