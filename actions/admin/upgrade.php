<?php
/**
 * Runs batch upgrades
 */

$guid = (int) get_input('guid');

$upgrade = get_entity($guid);

try {
	if (!$upgrade instanceof \ElggUpgrade) {
		throw new RuntimeException(elgg_echo('admin:upgrades:error:invalid_upgrade', [$upgrade->getDisplayName(), $guid]));
	}

	$result = _elgg_services()->upgrades->executeUpgrade($upgrade);

	$pending = _elgg_services()->upgrades->getPendingUpgrades();
	if (empty($pending)) {
		elgg_delete_admin_notice('pending_upgrades');
	}

	return elgg_ok_response($result);
} catch (RuntimeException $ex) {
	return elgg_error_response($ex->getMessage(), REFERRER, ELGG_HTTP_INTERNAL_SERVER_ERROR);
}
