<?php
/**
 * Elgg bookmarks save action
 */


$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());

$values = [];
$fields = elgg()->fields->get('object', 'bookmarks');
foreach ($fields as $field) {
	$value = null;
	
	$name = (string) elgg_extract('name', $field);
	switch (elgg_extract('#type', $field)) {
		case 'tags':
			$value = elgg_string_to_array((string) get_input($name));
			break;
		case 'url':
			$value = get_input($name);
			
			// don't use elgg_normalize_url() because we don't want
			// relative links resolved to this site.
			if (!empty($value) && !preg_match('#^((ht|f)tps?:)?//#i', $value)) {
				$value = "http://{$value}";
			}
			
			if (!filter_var($value, FILTER_VALIDATE_URL)) {
				return elgg_error_response(elgg_echo('bookmarks:save:failed'));
			}
			break;
		default:
			if ($name === 'title') {
				$value = elgg_get_title_input($name);
			} else {
				$value = get_input($name);
			}
			break;
	}
	
	if (elgg_extract('required', $field) && elgg_is_empty($value)) {
		return elgg_error_response(elgg_echo('bookmarks:save:failed'));
	}
	
	$values[$name] = $value;
}

$new = true;
if (empty($guid)) {
	$bookmark = new \ElggBookmark;
	$bookmark->container_guid = $container_guid;
} else {
	$bookmark = get_entity($guid);
	if (!$bookmark instanceof \ElggBookmark || !$bookmark->canEdit()) {
		return elgg_error_response(elgg_echo('bookmarks:save:failed'));
	}
	
	$new = false;
}

foreach ($values as $name => $value) {
	$bookmark->{$name} = $value;
}

if (!$bookmark->save()) {
	return elgg_error_response(elgg_echo('bookmarks:save:failed'));
}

//add to river only if new
if ($new) {
	elgg_create_river_item([
		'view' => 'river/object/bookmarks/create',
		'action_type' => 'create',
		'object_guid' => $bookmark->guid,
		'target_guid' => $bookmark->container_guid,
	]);
}

return elgg_ok_response('', elgg_echo('bookmarks:save:success'), $bookmark->getURL());
