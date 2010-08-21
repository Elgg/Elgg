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

		$query = "UPDATE {$CONFIG->dbprefix}access_collections SET owner_guid = $group->guid WHERE id = $acl";
		update_data($query);
	}
}
elgg_set_ignore_access(FALSE);

