<?php
/**
 * Make sure all users have the relationship member_of_site
 */
global $DB_QUERY_CACHE, $DB_PROFILE, $ENTITY_CACHE, $CONFIG;
$db_prefix = get_config('dbprefix');

$limit = 100;

$q = "SELECT e.* FROM {$db_prefix}entities e
	WHERE e.type = 'user' AND e.guid NOT IN (
		SELECT guid_one FROM {$db_prefix}entity_relationships
			WHERE guid_two = 1 AND relationship = 'member_of_site'
		)
	LIMIT $limit";

$users = get_data($q);

while ($users) {
	$DB_QUERY_CACHE = $DB_PROFILE = $ENTITY_CACHE = array();

	// do manually to not trigger any events because these aren't new users.
	foreach ($users as $user) {
		$rel_q = "INSERT INTO {$db_prefix}entity_relationships VALUES (
			'',
			'$user->guid',
			'member_of_site',
			'$user->site_guid',
			'$user->time_created'
		)";

		insert_data($rel_q);
	}

	// every time we run this query we've just reduced the rows it returns by $limit
	// so don't pass an offset.
	$q = "SELECT e.* FROM {$db_prefix}entities e
		WHERE e.type = 'user' AND e.guid NOT IN (
			SELECT guid_one FROM {$db_prefix}entity_relationships
				WHERE guid_two = 1 AND relationship = 'member_of_site'
			)
		LIMIT $limit";

	$users = get_data($q);
}