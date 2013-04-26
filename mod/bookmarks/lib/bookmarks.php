<?php
/**
 * Bookmarks helper functions
 *
 * @package Bookmarks
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $bookmark A bookmark object.
 * @return array
 */
function bookmarks_prepare_form_vars($bookmark = null) {
	// input names => defaults
	$values = array(
		'title' => get_input('title', ''), // bookmarklet support
		'address' => get_input('address', ''),
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'shares' => array(),
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $bookmark,
	);

	if ($bookmark) {
		foreach (array_keys($values) as $field) {
			if (isset($bookmark->$field)) {
				$values[$field] = $bookmark->$field;
			}
		}
	}

	if (elgg_is_sticky_form('bookmarks')) {
		$sticky_values = elgg_get_sticky_values('bookmarks');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('bookmarks');

	return $values;
}

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $bookmark A bookmark object.
 * @return array
 */
function bookmarks_prepare_container_options_values($user_guid = null) {
	if (!$user_guid) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	$container_options_values = array($user_guid => elgg_echo('bookmarks:container:me'));
	foreach (get_users_membership($user_guid) as $group) {
		if (can_write_to_container($user_guid, $group->guid, 'bookmarks')) {
			$container_options_values[$group->guid] = elgg_echo('groups:acl', array($group->name));
		}
	}
	return $container_options_values;
}