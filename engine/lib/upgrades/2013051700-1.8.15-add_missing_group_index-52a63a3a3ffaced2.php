<?php
/**
 * Elgg 1.8.15 upgrade 2013051700
 * add_missing_group_index
 *
 * Some Elgg sites are missing the groups_entity full text index on name and
 * description. This checks if it exists and adds it if it does not.
 */

$db_prefix = elgg_get_config('dbprefix');

$full_text_index_exists = false;
$results = get_data("SHOW INDEX FROM {$db_prefix}groups_entity");
if ($results) {
	foreach ($results as $result) {
		if ($result->Index_type === 'FULLTEXT') {
			$full_text_index_exists = true;
		}
	}
}

if ($full_text_index_exists == false) {
	$query = "ALTER TABLE {$db_prefix}groups_entity 
		ADD FULLTEXT name_2 (name, description)";
	if (!update_data($query)) {
		elgg_log("Failed to add full text index to groups_entity table", 'ERROR');
	}
}
