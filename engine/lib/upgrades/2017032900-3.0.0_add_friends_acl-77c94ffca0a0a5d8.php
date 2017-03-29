<?php
/**
 * Elgg 3.0.0 upgrade 2017032900
 * add_friends_acl
 *
 * Introduces friends acls
 */


set_time_limit(0);
$show_hidden = access_show_hidden_entities(true);

$db = _elgg_services()->db;
	
// add subtype to access collections table
$db->updateData("
	ALTER TABLE {$db->prefix}access_collections
	ADD COLUMN `subtype` varchar(255) NOT NULL DEFAULT '' AFTER `name`
");

// loop all users
$users = elgg_get_entities([
	'type' => 'user',
	'limit' => false,
	'batch' => true,
]);

foreach ($users as $user) {
	// create friends acl for all users
	$acl_id = create_access_collection('friends', $user->guid, 'friends');
	if (!$acl_id) {
		continue;
	}

	// add friends as members of newly create acl
	$acl_query = "
		INSERT INTO {$db->prefix}access_collection_membership
			SET access_collection_id = {$acl_id},
			    user_guid = :user_guid
			ON DUPLICATE KEY UPDATE user_guid = user_guid
	";
	
	elgg_get_entities_from_relationship([
		'relationship' => 'friend',
		'relationship_guid' => $user->guid,
		'type' => 'user',
		'limit' => false,
		'callback' => function ($row) use ($db, $acl_query) {
			
			$db->insertData($acl_query, [
				':user_guid' => (int) $row->guid,
			]);
			
			return $row->guid;
		},
	]);
		
	// update ACCESS_FRIENDS of user to new ACL id
	$friends_entities = elgg_get_entities([
		'owner_guid' => $user->guid,
		'limit' => false,
		'batch' => true,
		'wheres' => [
			'e.access_id = ' . ACCESS_FRIENDS,
		],
	]);
	
	// need to do normal entity update to allow entities to auto update child entities
	foreach ($friends_entities as $entity) {
		$entity->access_id = $acl_id;
		$entity->save();
	}
}

// restore hidden access
access_show_hidden_entities($show_hidden);
