<?php

/**
 * Elgg core search.
 *
 * @package Elgg
 * @subpackage Search
 */

/**
 * Get objects that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @deprecated 1.10 Do not call this function directly, use elgg_trigger_plugin_hook('search', 'object', $params)
 */
function search_objects_hook($hook, $type, $value, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_trigger_plugin_hook(\'search\', \'object\', $params)', '1.10');
	return elgg_trigger_plugin_hook($hook, $type, $params, $value);
}

/**
 * Get groups that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @deprecated 1.10 Do not call this function directly, use elgg_trigger_plugin_hook('search', 'group', $params)
 */
function search_groups_hook($hook, $type, $value, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_trigger_plugin_hook(\'search\', \'group\', $params)', '1.10');
	return elgg_trigger_plugin_hook($hook, $type, $params, $value);
}

/**
 * Get users that match the search parameters.
 *
 * Searches on username, display name, and profile fields
 * 
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @deprecated 1.10 Do not call this function directly, use elgg_trigger_plugin_hook('search', 'user', $params)
 */
function search_users_hook($hook, $type, $value, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_trigger_plugin_hook(\'search\', \'user\', $params)', '1.10');
	return elgg_trigger_plugin_hook($hook, $type, $params, $value);
}

/**
 * Get entities with tags that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @deprecated 1.10 Do not call this function directly, use elgg_trigger_plugin_hook('search', 'tags', $params)
 */
function search_tags_hook($hook, $type, $value, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_trigger_plugin_hook(\'search\', \'tags\', $params)', '1.10');
	return elgg_trigger_plugin_hook($hook, $type, $params, $value);
}

/**
 * Register tags as a custom search type.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Array of custom search types
 * @param array  $params Search parameters
 * @return array
 */
function search_custom_types_tags_hook($hook, $type, $value, $params) {
	$value[] = 'tags';
	return $value;
}
