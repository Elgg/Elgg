<?php
/**
 * Unlocks the upgrade script
 */

$mutex = _elgg_services()->mutex;

if ($mutex->isLocked('upgrade')) {
	$mutex->unlock('upgrade');
}

return elgg_ok_response('', elgg_echo('upgrade:unlock:success'));
