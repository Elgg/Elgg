<?php
/**
 *
 */

$guid = get_input('guid');

$upgrade = get_entity($guid);

if (!$upgrade instanceof \ElggUpgrade) {
	register_error(elgg_echo('admin:upgrades:error:invalid_upgrade', array($entity->title, $guid)));
	exit;
}

$upgrader = new \Elgg\BatchUpgrader;
$upgrader->setUpgrade($upgrade);
$upgrader->run();

echo json_encode($upgrader->getResult());