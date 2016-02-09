<?php
/**
 * Unlocks the upgrade script
 */

$mutex = _elgg_services()->mutex;

if ($mutex->isLocked('upgrade')) {
	$mutex->unlock('upgrade');
}
system_message(elgg_echo('upgrade:unlock:success'));
forward(REFERER);
