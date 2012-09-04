<?php
/**
 * Unlocks the upgrade script 
 */

if (_elgg_upgrade_is_locked()) {
	_elgg_upgrade_unlock();
}
system_message(elgg_echo('upgrade:unlock:success'));
forward(REFERER);
