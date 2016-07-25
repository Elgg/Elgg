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
$input = array();
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
$name = get_input('name', null, false);
if ($name !== null) {
	$input['name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
}

$user = elgg_get_logged_in_user_entity();

$group_guid = (int)get_input('group_guid');
$is_new_group = $group_guid == 0;

if ($is_new_group
		&& (elgg_get_plugin_setting('limited_groups', 'groups') == 'yes')
		&& !$user->isAdmin()) {
	register_error(elgg_echo("groups:cantcreate"));
	forward(REFERER);
}

$group = $group_guid ? get_entity($group_guid) : new ElggGroup();
if (elgg_instanceof($group, "group") && !$group->canEdit()) {
	register_error(elgg_echo("groups:cantedit"));
	forward(REFERER);
}

// Assume we can edit or this is a new group
foreach ($input as $shortname => $value) {
	// update access collection name if group name changes
	if (!$is_new_group && $shortname == 'name' && $value != $group->name) {
		$group_name = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
		$ac_name = sanitize_string(elgg_echo('groups:group') . ": " . $group_name);
		$acl = get_access_collection($group->group_acl);
		if ($acl) {
			// @todo Elgg api does not support updating access collection name
			$db_prefix = elgg_get_config('dbprefix');
			$query = "UPDATE {$db_prefix}access_collections SET name = '$ac_name'
				WHERE id = $group->group_acl";
			update_data($query);
		}
	}

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
	register_error(elgg_echo("groups:notitle"));
	forward(REFERER);
}

// Set group tool options (only pass along saved entities)
$tool_entity = !$is_new_group ? $group : null;
$tool_options = groups_get_group_tool_options($tool_entity);
if ($tool_options) {
	foreach ($tool_options as $group_option) {
		$option_toggle_name = $group_option->name . "_enable";
		$option_default = $group_option->default_on ? 'yes' : 'no';
		$value = get_input($option_toggle_name);

		// if already has option set, don't change if no submission
		if ($group->$option_toggle_name && $value === null) {
			continue;
		}

		$group->$option_toggle_name = $value ? $value : $option_default;
	}
}

// Group membership - should these be treated with same constants as access permissions?
$value = get_input('membership');
if ($group->membership === null || $value !== null) {
	$is_public_membership = ($value == ACCESS_PUBLIC);
	$group->membership = $is_public_membership ? ACCESS_PUBLIC : ACCESS_PRIVATE;
}

$group->setContentAccessMode((string)get_input('content_access_mode'));

if ($is_new_group) {
	$group->access_id = ACCESS_PUBLIC;
}

$old_owner_guid = $is_new_group ? 0 : $group->owner_guid;

$value = get_input('owner_guid');
$new_owner_guid = ($value === null) ? $old_owner_guid : (int)$value;

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

		$metadata = elgg_get_metadata(array(
			'guid' => $group_guid,
			'limit' => false,
		));
		if ($metadata) {
			foreach ($metadata as $md) {
				if ($md->owner_guid == $old_owner_guid) {
					$md->owner_guid = $new_owner_guid;
					$md->save();
				}
			}
		}
	}
}

if ($is_new_group) {
	// if new group, we need to save so group acl gets set in event handler
	if (!$group->save()) {
		register_error(elgg_echo("groups:save_error"));
		forward(REFERER);
	}
}

// Invisible group support
// @todo this requires save to be called to create the acl for the group. This
// is an odd requirement and should be removed. Either the acl creation happens
// in the action or the visibility moves to a plugin hook
if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {
	$value = get_input('vis');
	if ($is_new_group || $value !== null) {
		$visibility = (int)$value;

		if ($visibility == ACCESS_PRIVATE) {
			// Make this group visible only to group members. We need to use
			// ACCESS_PRIVATE on the form and convert it to group_acl here
			// because new groups do not have acl until they have been saved once.
			$visibility = $group->group_acl;

			// Force all new group content to be available only to members
			$group->setContentAccessMode(ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY);
		}

		$group->access_id = $visibility;
	}
}

if (!$group->save()) {
	register_error(elgg_echo("groups:save_error"));
	forward(REFERER);
}

// group saved so clear sticky form
elgg_clear_sticky_form('groups');

// group creator needs to be member of new group and river entry created
if ($is_new_group) {

	// @todo this should not be necessary...
	elgg_set_page_owner_guid($group->guid);

	$group->join($user);
	elgg_create_river_item(array(
		'view' => 'river/group/create',
		'action_type' => 'create',
		'subject_guid' => $user->guid,
		'object_guid' => $group->guid,
	));
}

$has_uploaded_icon = (!empty($_FILES['icon']['type']) && substr_count($_FILES['icon']['type'], 'image/'));

if ($has_uploaded_icon) {
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $group->owner_guid;
	$filehandler->setFilename("groups/$group->guid.jpg");
	$filehandler->open("write");
	$filehandler->write(get_uploaded_file('icon'));
	$filehandler->close();

	if ($filehandler->exists()) {
		// Non existent file throws exception
		$group->saveIconFromElggFile($filehandler);
	}
}

system_message(elgg_echo("groups:saved"));

forward($group->getUrl());
