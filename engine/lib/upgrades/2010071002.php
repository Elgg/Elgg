<?php
/**
 * Update the notifications based on all friends and access collections
 */

// loop through all users checking collections and notifications
global $DB_QUERY_CACHE, $DB_PROFILE, $ENTITY_CACHE, $CONFIG;
global $NOTIFICATION_HANDLERS;
$users = mysql_query("SELECT guid, username FROM {$CONFIG->dbprefix}users_entity
	WHERE username != ''");
while ($user = mysql_fetch_object($users)) {
	$DB_QUERY_CACHE = $DB_PROFILE = $ENTITY_CACHE = array();

	$user = get_entity($user->guid);
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$notify = "notify$method";
		$metaname = "collections_notifications_preferences_$method";
		$collections_preferences = $user->$metaname;
		if (!$collections_preferences) {
			continue;
		}
		if (!is_array($collections_preferences)) {
			$collections_preferences = array($collections_preferences);
		}
		foreach ($collections_preferences as $collection_id) {
			// check the all friends notifications
			if ($collection_id == -1) {
				$options = array(
					'relationship' => 'friend',
					'relationship_guid' => $user->guid,
					'limit' => 0
				);
				$friends = elgg_get_entities_from_relationship($options);
				foreach ($friends as $friend) {
					if (!check_entity_relationship($user->guid, $notify, $friend->guid)) {
						add_entity_relationship($user->guid, $notify, $friend->guid);
					}
				}
			} else {
				$members = get_members_of_access_collection($collection_id, TRUE);
				foreach ($members as $member) {
					if (!check_entity_relationship($user->guid, $notify, $members)) {
						add_entity_relationship($user->guid, $notify, $member);
					}
				}
			}
		}
	}
}
