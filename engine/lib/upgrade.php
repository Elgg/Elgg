<?php
/**
 * Elgg upgrade library.
 * Contains code for handling versioning and upgrades.
 */

/**
 * Perform some clean up when upgrade completes
 * @elgg_event complete upgrade
 * @return void
 */
function _elgg_upgrade_completed() {
	$pending = _elgg_services()->upgrades->getPendingUpgrades();
	if (empty($pending)) {
		elgg_delete_admin_notice('pending_upgrades');
	}
}
