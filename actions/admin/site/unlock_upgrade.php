<?php
/**
 * Unlocks the upgrade script
 */

$upgrader = new Elgg_UpgradeService();

if ($upgrader->isUpgradeLocked()) {
	$upgrader->releaseUpgradeMutex();
}
system_message(elgg_echo('upgrade:unlock:success'));
forward(REFERER);
