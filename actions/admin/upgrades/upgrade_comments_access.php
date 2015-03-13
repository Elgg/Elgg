<?php

/**
 * Convert comment annotations to entities
 * 
 * Run for 2 seconds per request as set by $batch_run_time_in_secs. This includes
 * the engine loading time.
 */
// from engine/start.php
global $START_MICROTIME;
$batch_run_time_in_secs = 2;

// if upgrade has run correctly, mark it done
if (get_input('upgrade_completed')) {
	// set the upgrade as completed
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath('admin/upgrades/commentaccess');
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}

	return true;
}

// Offset is the total amount of errors so far. We skip these
// comments to prevent them from possibly repeating the same error.
$offset = get_input('offset', 0);
$limit = 50;

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$success_count = 0;
$error_count = 0;

do {
	$dbprefix = elgg_get_config('dbprefix');
	$options = array(
		'type' => 'object',
		'subtype' => 'comment',
		'joins' => array(
			"JOIN {$dbprefix}entities e2 ON e.container_guid = e2.guid"
		),
		'wheres' => array(
			"e.access_id != e2.access_id"
		),
		'offset' => $offset,
		'limit' => $limit,
		'preload_containers' => true
	);
			
	$comments = elgg_get_entities($options);
	
	foreach ($comments as $comment) {
		$container = $comment->getContainerEntity();
		
		if (!$container) {
			$error_count++;
			continue;
		}		
		
		$comment->access_id = $container->access_id;
		
		if ($comment->save()) {
			$success_count++;
		} else {
			$error_count++;
		}
	}
	
} while ((microtime(true) - $START_MICROTIME) < $batch_run_time_in_secs);

access_show_hidden_entities($access_status);

// Give some feedback for the UI
echo json_encode(array(
	'numSuccess' => $success_count,
	'numErrors' => $error_count,
));
