<?php

/**
 * Make sure that everyone who belongs to a group is a member of the group's access collection
 */


elgg_set_ignore_access(TRUE);

$params = array('type' => 'group', 'limit' => 0);
$groups = elgg_get_entities($params);
if ($groups) {
	foreach ($groups as $group) {
		$acl = $group->group_acl;

		$query = "SELECT u.guid FROM {$CONFIG->dbprefix}users_entity u
			JOIN {$CONFIG->dbprefix}entity_relationships r
				ON u.guid = r.guid_one AND r.relationship = 'member' AND r.guid_two = $group->guid
			LEFT JOIN {$CONFIG->dbprefix}access_collection_membership a
				ON u.guid = a.user_guid AND a.access_collection_id = $acl
				WHERE a.user_guid IS NULL";

		$results = get_data($query);
		if ($results != FALSE) {
			foreach ($results as $user) {
				$insert = "INSERT INTO {$CONFIG->dbprefix}access_collection_membership
							(user_guid, access_collection_id) VALUES ($user->guid, $acl)";
				insert_data($insert);
			}
		}
	}
}
elgg_set_ignore_access(FALSE);
