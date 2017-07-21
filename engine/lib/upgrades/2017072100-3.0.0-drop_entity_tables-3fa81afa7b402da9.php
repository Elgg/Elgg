<?php
/**
 * Elgg 3.0.0 upgrade 2016112500
 * drop_entity_tables
 *
 * Migrates entity table data to metadata and drops the entity specific tables
 */

$db = _elgg_services()->db;

$ia = elgg_set_ignore_access(true);
$show_hidden = access_show_hidden_entities(true);

$convert = [
	'groups_entity' => [
		'name', 
		'description',
	],
	'objects_entity' => [
		'title', 
		'description',
	],
	'sites_entity' => [
		'name', 
		'description',
		'url',
	],
	'users_entity' => [
		'name', 
		'username',
		'password_hash',
		'email',
		'language',
		'banned',
		'admin',
		'last_action',
		'prev_last_action',
		'last_login',
		'prev_last_login',
	],
	
];

foreach ($convert as $table_name => $attributes) {
	$entities = $db->getData("SELECT * FROM {$db->prefix}{$table_name}");
	
	foreach ($entities as $entity_row) {
		$entity = get_entity($entity_row->guid);
		if (empty ($entity)) {
			// this is orphaned data so no need to migrate
			continue;
		}
		
		// save as metadata
		// we can do this as the api already uses the new storage location
		foreach ($attributes as $attribute) {
			$entity->{$attribute} = $entity_row->{$attribute};
		}
	}
	
	// done with migrate... drop the table
	$db->deleteData("
		DROP TABLE {$db->prefix}{$table_name}
	");
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($show_hidden);
