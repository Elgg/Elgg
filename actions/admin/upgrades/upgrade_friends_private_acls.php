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
	$upgrade = $factory->getUpgradeFromPath('admin/upgrades/friendsprivateacls');
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}

	return true;
}

// Offset is the total amount of processed users so far.
$offset = (int) get_input('processed', 0);
$limit = 50;

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$success_count = 0;
$error_count = 0;

do {
	$dbprefix = elgg_get_config('dbprefix');
	$options = array(
		'type' => 'user',
		'offset' => $offset,
		'limit' => $limit,
	);
			
	$users = elgg_get_entities($options);
	
	foreach ($users as $user) {
		$f_acl = false;
		$p_acl = false;
		if ($user->friends_acl) {
			$f_acl = get_access_collection($user->friends_acl); // use existing if there is one
		}
		if ($user->private_acl) {
			$p_acl = get_access_collection($user->private_acl);
		}
		
		if (!$f_acl) {
			$f_acl_id = create_access_collection('acl:friends:' . $user->guid, elgg_get_site_entity()->guid);
		} else {
			$q = "DELETE FROM {$dbprefix}access_collection_membership
				WHERE access_collection_id = {$f_acl->id}";
			delete_data($q);
			$f_acl_id = $f_acl->id;
		}
		
		if (!$p_acl) {
			$p_acl_id = create_access_collection('acl:private:' . $user->guid, elgg_get_site_entity()->guid);
		} else {
			$q = "DELETE FROM {$dbprefix}access_collection_membership
				WHERE access_collection_id = {$p_acl->id}";
			delete_data($q);
			$p_acl_id = $p_acl->id;
		}
		
		if (!$f_acl_id || !$p_acl_id) {
			$error_count++;
		}
		
		$user->friends_acl = $f_acl_id;
		$user->private_acl = $p_acl_id;
		
		// add user to their own private acl
		add_user_to_access_collection($user->guid, $p_acl_id);
		
		// add user to their own friends collection
		// so they still have access if ownership changes on things assigned to their friends
		add_user_to_access_collection($user->guid, $f_acl_id);
		
		// add their friends
		$params = array(
			'type' => 'user',
			'relationship' => 'friend',
			'relationship_guid' => $user->guid,
			'limit' => false,
			'callback' => false
		);
		
		$friends = new \ElggBatch('elgg_get_entities_from_relationship', $params);
		foreach ($friends as $friend) {
			add_user_to_access_collection($friend->guid, $f_acl_id);
		}
		
		// update their content
		$params = array(
			'owner_guid' => $user->guid,
			'wheres' => array(
				"access_id IN (0, -2)"
			),
			'limit' => false
		);

		
		$content = new \ElggBatch('elgg_get_entities', $params, null, 25, false);
		foreach ($content as $c) {
			// skip comments and discussion replies
			// they will be handled automagically to match their container
			if ($c->type == 'object' && (in_array($c->getSubtype(), array('comment', 'discussion_reply')))) {
				continue;
			}
			
			if ($c->access_id == 0) {
				$c->access_id = $p_acl_id;
			} else {
				$c->access_id = $f_acl_id;
			}
			
			$c->save();
		}
		
		// catch any metadata/annotations that aren't handled automagically
		$pmsql = "UPDATE {$dbprefix}metadata SET access_id = {$p_acl_id} WHERE access_id = 0 AND owner_guid = {$user->guid}";
		update_data($pmsql);
		
		$fmsql = "UPDATE {$dbprefix}metadata SET access_id = {$f_acl_id} WHERE access_id = -2 AND owner_guid = {$user->guid}";
		update_data($fmsql);
		
		$pasql = "UPDATE {$dbprefix}annotations SET access_id = {$p_acl_id} WHERE access_id = 0 AND owner_guid = {$user->guid}";
		update_data($pasql);
		
		$fasql = "UPDATE {$dbprefix}annotations SET access_id = {$f_acl_id} WHERE access_id = 0 AND owner_guid = {$user->guid}";
		update_data($fasql);
		
		$success_count++;
	}
	
} while ((microtime(true) - $START_MICROTIME) < $batch_run_time_in_secs);

access_show_hidden_entities($access_status);

// Give some feedback for the UI
echo json_encode(array(
	'numSuccess' => $success_count,
	'numErrors' => $error_count,
));
