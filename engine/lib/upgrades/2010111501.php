<?php
/**
 * Set validation metadata on unvalidated users to false rather than 
 * not existing. This is needed because of the change in how validation is
 * being handled.
 */

// turn off system log because of all the metadata this can create
elgg_unregister_event_handler('all', 'all', 'system_log_listener');
elgg_unregister_event_handler('log', 'systemlog', 'system_log_default_logger');

$ia = elgg_set_ignore_access(TRUE);
$hidden_entities = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

$validated_id = get_metastring_id('validated');
$one_id = get_metastring_id(1);

$query = "SELECT guid FROM {$CONFIG->dbprefix}entities e
			WHERE e.type = 'user' AND e.enabled = 'no' AND
			NOT EXISTS (
				SELECT 1 FROM {$CONFIG->dbprefix}metadata md
				WHERE md.entity_guid = e.guid
				AND md.name_id = $validated_id
				AND md.value_id = $one_id)";

$user_guids = mysql_query($query);
while ($user_guid = mysql_fetch_object($user_guids)) {
	create_metadata($user_guid->guid, 'validated', false, '', 0, ACCESS_PUBLIC, false);
}

access_show_hidden_entities($hidden_entities);
elgg_set_ignore_access($ia);
