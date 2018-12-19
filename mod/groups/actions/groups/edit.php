<?php
/**
 * Elgg groups plugin edit action.
 *
 * If editing an existing group, only the "group_guid" must be submitted. All other form
 * elements may be omitted and the corresponding data will be left as is.
 *
 * @package ElggGroups
 */

elgg_make_sticky_form('groups');

// Get group fields
$input = [];
foreach (elgg_get_config('group') as $shortname => $valuetype) {
	$value = get_input($shortname);

	if ($value === null) {
		// only submitted fields should be updated
		continue;
	}

	$input[$shortname] = $value;

	// @todo treat profile fields as unescaped: don't filter, encode on output
	if (is_array($input[$shortname])) {
		array_walk_recursive($input[$shortname], function (&$v) {
			$v = elgg_html_decode($v);
		});
	} else {
		$input[$shortname] = elgg_html_decode($input[$shortname]);
	}

	if ($valuetype == 'tags') {
		$input[$shortname] = string_to_tag_array($input[$shortname]);
	}
}

// only set if submitted
$name = elgg_get_title_input('name', null);
if ($name !== null) {
	$input['name'] = $name;
}

$user = elgg_get_logged_in_user_entity();

$group_guid = (int) get_input('group_guid');

if ($group_guid) {
	$is_new_group = false;
	$group = get_entity($group_guid);
	if (!$group instanceof ElggGroup || !$group->canEdit()) {
		$error = elgg_echo('groups:cantedit');
		return elgg_error_response($error);
	}
} else {
	if (elgg_get_plugin_setting('limited_groups', 'groups') == 'yes' && !$user->isAdmin()) {
		$error = elgg_echo('groups:cantcreate');
		return elgg_error_response($error);
	}
	
	$container_guid = get_input('container_guid', $user->guid);
	$container = get_entity($container_guid);
	
	if (!$container || !$container->canWriteToContainer($user->guid, 'group')) {
		$error = elgg_echo('groups:cantcreate');
		return elgg_error_response($error);
	}
	
	$is_new_group = true;
	$group = new ElggGroup();
	$group->container_guid = $container->guid;
}

// Assume we can edit or this is a new group
foreach ($input as $shortname => $value) {
	if ($value === '' && !in_array($shortname, ['name', 'description'])) {
		// The group profile displays all profile fields that have a value.
		// We don't want to display fields with empty string value, so we
		// remove the metadata completely.
		$group->deleteMetadata($shortname);
		continue;
	}

	$group->$shortname = $value;
}

// Validate create
if (!$group->name) {
	return elgg_error_response(elgg_echo('groups:notitle'));
}

// Set group tool options (only pass along saved entities)
// @todo: move this to an event handler to make sure groups created outside of the action
// get their tools configured
if ($is_new_group) {
	$tools = elgg()->group_tools->all();
} else {
	$tools = elgg()->group_tools->group($group);
}

foreach ($tools as $tool) {
	$prop_name = $tool->mapMetadataName();
	$value = get_input($prop_name);

	if (!isset($value)) {
		continue;
	}

	if ($value === 'yes') {
		$group->enableTool($tool->name);
	} else {
		$group->disableTool($tool->name);
	}
}

// Group membership - should these be treated with same constants as access permissions?
$value = get_input('membership');
if ($group->membership === null || $value !== null) {
	$is_public_membership = ($value == ACCESS_PUBLIC);
	$group->membership = $is_public_membership ? ACCESS_PUBLIC : ACCESS_PRIVATE;
}

$group->setContentAccessMode((string) get_input('content_access_mode'));

if ($is_new_group) {
	$group->access_id = ACCESS_PUBLIC;
}

$old_owner_guid = $is_new_group ? 0 : $group->owner_guid;

$value = (array) get_input('owner_guid');
$new_owner_guid = empty($value) ? $old_owner_guid : (int) $value[0];

if (!$is_new_group && $new_owner_guid && $new_owner_guid != $old_owner_guid) {
	// verify new owner is member and old owner/admin is logged in
	if ($group->isMember(get_user($new_owner_guid)) && ($old_owner_guid == $user->guid || $user->isAdmin())) {
		$group->owner_guid = $new_owner_guid;
		if ($group->container_guid == $old_owner_guid) {
			// Even though this action defaults container_guid to the logged in user guid,
			// the group may have initially been created with a custom script that assigned
			// a different container entity. We want to make sure we preserve the original
			// container if it the group is not contained by the original owner.
			$group->container_guid = $new_owner_guid;
		}
	}
}

if ($is_new_group) {
	// if new group, we need to save so group acl gets set in event handler
	if (!$group->save()) {
		return elgg_error_response(elgg_echo('groups:save_error'));
	}
}

// Invisible group support
// @todo this requires save to be called to create the acl for the group. This
// is an odd requirement and should be removed. Either the acl creation happens
// in the action or the visibility moves to a plugin hook
if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {
	$value = get_input('vis');
	if ($is_new_group || $value !== null) {
		$visibility = (int) $value;

		if ($visibility == ACCESS_PRIVATE) {
			// Make this group visible only to group members. We need to use
			// ACCESS_PRIVATE on the form and convert it to group_acl here
			// because new groups do not have acl until they have been saved once.
			$acl = _groups_get_group_acl($group);
			if ($acl) {
				$visibility = $acl->id;
			}
			
			// Force all new group content to be available only to members
			$group->setContentAccessMode(ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY);
		}

		$group->access_id = $visibility;
	}
}

if (!$group->save()) {
	return elgg_error_response(elgg_echo('groups:save_error'));
}

// group saved so clear sticky form
elgg_clear_sticky_form('groups');

// group creator needs to be member of new group and river entry created
if ($is_new_group) {
	// @todo this should not be necessary...
	elgg_set_page_owner_guid($group->guid);

	$group->join($user);
	elgg_create_river_item([
		'view' => 'river/group/create',
		'action_type' => 'create',
		'object_guid' => $group->guid,
	]);
}

if (get_input('icon_remove')) {
	$group->deleteIcon();
} else {
	// try to save new icon, will fail silently if no icon provided
	$group->saveIconFromUploadedFile('icon');
}

$data = [
	'entity' => $group,
];
return elgg_ok_response($data, elgg_echo('groups:saved'), $group->getURL());
