<?php
/**
 * Bundle all functions which have been deprecated in Elgg 7.2
 */

/**
 * Get the admin users
 *
 * @param array $options Options array, @see elgg_get_entities() for parameters
 *
 * @return mixed Array of admin users or false on failure. If a count, returns int.
 * @since 1.8.0
 * @deprecated 7.2 Use elgg_get_entities()
 */
function elgg_get_admins(array $options = []) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_get_entities().', '7.2');
	
	$options['type'] = 'user';
	$options['metadata_name_value_pairs'] = elgg_extract('metadata_name_value_pairs', $options, []);

	$options['metadata_name_value_pairs']['admin'] = 'yes';

	return elgg_get_entities($options);
}
