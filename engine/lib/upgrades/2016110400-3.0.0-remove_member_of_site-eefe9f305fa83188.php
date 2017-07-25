<?php
/**
 * Elgg 3.0.0 upgrade 2016110400
 * remove_member_of_site
 *
 * Removes all member_of_site relationships in the relationships table
 */

$dbprefix = elgg_get_config('dbprefix');

delete_data("DELETE FROM {$dbprefix}entity_relationships
	WHERE relationship = 'member_of_site'");
