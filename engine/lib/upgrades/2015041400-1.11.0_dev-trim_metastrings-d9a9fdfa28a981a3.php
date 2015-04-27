<?php
/**
 * Elgg 1.11.0-dev upgrade 2015041400
 * trim_metastrings
 *
 * Trim all whitespace padding in metastrings
 */

$prefix = elgg_get_config('dbprefix');
$q = "SELECT * FROM {$prefix}metastrings WHERE string REXEXP '^[ ]+|[ ]+$'";
$r = mysql_query($q);

while ($ms = mysql_fetch_assoc($r)) {
	// find if trimmed version collides with existing MS
	$string = sanitize_string(trim($ms['string']));
	$existing = get_data_row("SELECT * FROM {$prefix}metastrings WHERE string = BINARY '$string' LIMIT 1");
	
	if ($existing) {
		// delete the padded string and update MD / annotations to use the existing trimmed one
		$q = "DELETE FROM {$prefix}metastrings WHERE id = {$ms['id']} LIMIT 1";
		if (delete_data($q)) {
			update_data("UPDATE {$prefix}metadata SET name_id = '{$existing->id}' where name_id = '{$ms['id']}'");
			update_data("UPDATE {$prefix}metadata SET value_id = '{$existing->id}' where value_id = '{$ms['id']}'");
			
			update_data("UPDATE {$prefix}annotations SET name_id = '{$existing->id}' where name_id = '{$ms['id']}'");
			update_data("UPDATE {$prefix}annotations SET value_id = '{$existing->id}' where value_id = '{$ms['id']}'");
		}
	} else {
		update_data("UPDATE {$prefix}metastrings SET string = '$string' where id = '{$ms['id']}'");
	}
}
