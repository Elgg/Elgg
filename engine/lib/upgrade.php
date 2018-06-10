<?php
/**
 * Elgg upgrade library.
 * Contains code for handling versioning and upgrades.
 *
 * @package    Elgg.Core
 * @subpackage Upgrade
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

return function (\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('complete', 'upgrade', '_elgg_upgrade_completed');
};