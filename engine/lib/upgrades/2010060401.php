<?php

/**
 * Get each user's notify* relationships and confirm that they have a friend
 * or member relationship depending on type. This fixes the notify relationships
 * that were not updated to due to #1837
 */

$count = 0;

$user_guids = mysql_query("SELECT guid FROM {$CONFIG->dbprefix}users_entity");
while ($user = mysql_fetch_object($user_guids)) {

	$query = "SELECT * FROM {$CONFIG->dbprefix}entity_relationships
		WHERE guid_one=$user->guid AND relationship LIKE 'notify%'";
	$relationships = mysql_query($query);
	if (mysql_num_rows($relationships) == 0) {
		// no notify relationships for this user
		continue;
	}

	while ($obj = mysql_fetch_object($relationships)) {
		$query = "SELECT type FROM {$CONFIG->dbprefix}entities WHERE guid=$obj->guid_two";
		$results = mysql_query($query);
		if (mysql_num_rows($results) == 0) {
			// entity doesn't exist - shouldn't be possible
			continue;
		}

		$entity = mysql_fetch_object($results);

		switch ($entity->type) {
			case 'user':
				$relationship_type = 'friend';
				break;
			case 'group':
				$relationship_type = 'member';
				break;
		}

		if (isset($relationship_type)) {
				$query = "SELECT * FROM {$CONFIG->dbprefix}entity_relationships
							WHERE guid_one=$user->guid AND relationship='$relationship_type'
							AND guid_two=$obj->guid_two";
				$results = mysql_query($query);

			if (mysql_num_rows($results) == 0) {
				$query = "DELETE FROM {$CONFIG->dbprefix}entity_relationships WHERE id=$obj->id";
				mysql_query($query);
				$count++;
			}
		}
	}

}

if (is_callable('error_log')) {
	error_log("Deleted $count notify relationships in upgrade");
}
