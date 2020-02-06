<?php
/**
 * Runs batch upgrades
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$guid = (int) get_input('guid');

$upgrade = get_entity($guid);

if (!$upgrade instanceof \ElggUpgrade) {
	$msg = elgg_echo('admin:upgrades:error:invalid_upgrade', [$guid]);
	throw new EntityNotFoundException($msg);
}

$result = _elgg_services()->upgrades->executeUpgrade($upgrade);

return elgg_ok_response($result->toArray());
