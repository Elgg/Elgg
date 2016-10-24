<?php
/**
 * Runs batch upgrades
 */

$guid = get_input('guid');

$upgrade = get_entity($guid);

try {
	if (!$upgrade instanceof \ElggUpgrade) {
		throw new RuntimeException(elgg_echo('admin:upgrades:error:invalid_upgrade', [$entity->title, $guid]));
	}

	$result = _elgg_services()->batchUpgrader->run($upgrade);
	return elgg_ok_response($result);
} catch (RuntimeException $ex) {
	return elgg_error_response($ex->getMessage(), REFERRER, ELGG_HTTP_INTERNAL_SERVER_ERROR);
}
