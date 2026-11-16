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

/**
 * Write a persistent message to the admin view.
 * Useful to alert the admin to take a certain action.
 * The id is a unique ID that can be cleared once the admin
 * completes the action.
 *
 * eg: add_admin_notice('twitter_services_no_api',
 * 	'Before your users can use Twitter services on this site, you must set up
 * 	the Twitter API key in the <a href="link">Twitter Services Settings</a>');
 *
 * @param string $id      A unique ID that your plugin can remember
 * @param string $message Body of the message
 *
 * @return ElggAdminNotice|null
 * @since 1.8.0
 * @deprecated 7.2
 */
function elgg_add_admin_notice(string $id, string $message): ?\ElggAdminNotice {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '7.2');
	
	return _elgg_services()->adminNotices->add($id, $message);
}

/**
 * Remove an admin notice by ID.
 *
 * @param string $id The unique ID assigned in add_admin_notice()
 *
 * @return bool
 * @since 1.8.0
 * @deprecated 7.2
 */
function elgg_delete_admin_notice(string $id): bool {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '7.2');
	
	return _elgg_services()->adminNotices->delete($id);
}

/**
 * Get admin notices. An admin must be logged in since the notices are private.
 *
 * @param array $options Query options
 *
 * @return \ElggObject[]|int|mixed Admin notices
 * @since 1.8.0
 * @deprecated 7.2
 */
function elgg_get_admin_notices(array $options = []) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '7.2');
	
	return _elgg_services()->adminNotices->find($options);
}

/**
 * Check if an admin notice is currently active. (Ignores access)
 *
 * @param string $id The unique ID used to register the notice.
 *
 * @return bool
 * @since 1.8.0
 * @deprecated 7.2
 */
function elgg_admin_notice_exists(string $id): bool {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '7.2');
	
	return _elgg_services()->adminNotices->exists($id);
}
