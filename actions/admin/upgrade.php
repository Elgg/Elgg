<?php
/**
 * Runs batch upgrades
 */

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$upgrade = get_entity($guid);
if (!$upgrade instanceof \ElggUpgrade) {
	return elgg_error_response(elgg_echo('admin:upgrades:error:invalid_upgrade', [$guid]), REFERER, ELGG_HTTP_NOT_FOUND);
}

$result = _elgg_services()->upgrades->executeUpgrade($upgrade);

return elgg_ok_response($result->toArray());
