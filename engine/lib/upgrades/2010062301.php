<?php

/**
 * Change ownership of group ACLs to group entity
 */

elgg_set_ignore_access(TRUE);

$params = array('type' => 'group',
				'limit' => 0);
$groups = elgg_get_entities($params);
if ($groups) {
	foreach ($groups as $group) {
		$acl = $group->group_acl;

		try {
			$query = "UPDATE {$CONFIG->dbprefix}access_collections
				SET owner_guid = $group->guid WHERE id = $acl";
			update_data($query);
		} catch (Exception $e) {
			// no acl so create one
			$ac_name = elgg_echo('groups:group') . ": " . $group->name;
			$group_acl = create_access_collection($ac_name, $group->guid);
			if ($group_acl) {
				create_metadata($group->guid, 'group_acl', $group_acl, 'integer', $group->owner_guid);
				$object->group_acl = $group_id;
			}
		}

	}
}
elgg_set_ignore_access(FALSE);

