<?php
/**
 * Elgg projects plugin edit action.
 *
 * @package Coopfunding
 * @subpackage Projects
 */

elgg_make_sticky_form('projects');

/**
 * wrapper for recursive array walk decoding
 */
function profile_array_decoder(&$v) {
	$v = _elgg_html_decode($v);
}

// Get project fields
$input = array();
foreach (elgg_get_config('project') as $shortname => $valuetype) {
	$input[$shortname] = get_input($shortname);

	// @todo treat profile fields as unescaped: don't filter, encode on output
	if (is_array($input[$shortname])) {
		array_walk_recursive($input[$shortname], 'profile_array_decoder');
	} else {
		$input[$shortname] = _elgg_html_decode($input[$shortname]);
	}

	if ($valuetype == 'tags') {
		$input[$shortname] = string_to_tag_array($input[$shortname]);
	}
}

$input['name'] = htmlspecialchars(get_input('name', '', false), ENT_QUOTES, 'UTF-8');
$input['alias'] = htmlspecialchars(get_input('alias', '', false), ENT_QUOTES, 'UTF-8');

$user = elgg_get_logged_in_user_entity();

if ($project_guid = (int)get_input('project_guid')) {
	$project = new ElggGroup($project_guid);
	if (!$project->canEdit()) {
		register_error(elgg_echo("projects:cantedit"));
		forward(REFERER);
	}
} else {
	$project = new ElggGroup();
	$project->subtype = 'project';
	$is_new_project = true;
}

elgg_load_library('elgg:projects');

if (!isset($input['alias'])) {
	register_error(elgg_echo('projects:alias:missing'));
	forward(REFERER);
} elseif (!preg_match("/^[a-zA-Z0-9\-]{2,32}$/", $input['alias'])) {
	register_error(elgg_echo('projects:alias:invalidchars'));
	forward(REFERER);
} elseif ($project->alias != $input['alias'] && projects_get_from_alias($input['alias'])) {
	register_error(elgg_echo('projects:alias:already_used'));
	forward(REFERER);
}

// Assume we can edit or this is a new project
if (sizeof($input) > 0) {
	foreach($input as $shortname => $value) {
		// update access collection name if project name changes
		if (!$is_new_project && $shortname == 'name' && $value != $project->name) {
			$project_name = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
			$ac_name = sanitize_string(elgg_echo('projects:project') . ": " . $project_name);
			$acl = get_access_collection($project->group_acl);
			if ($acl) {
				// @todo Elgg api does not support updating access collection name
				$db_prefix = elgg_get_config('dbprefix');
				$query = "UPDATE {$db_prefix}access_collections SET name = '$ac_name' 
					WHERE id = $project->group_acl";
				update_data($query);
			}
		}

		$project->$shortname = $value;
	}
}

// Validate create
if (!$project->name) {
	register_error(elgg_echo("projects:notitle"));
	forward(REFERER);
}


// Set project tool options
$tool_options = elgg_get_config('project_tool_options');
if ($tool_options) {
	foreach ($tool_options as $project_option) {
		$option_toggle_name = $project_option->name . "_enable";
		$option_default = $project_option->default_on ? 'yes' : 'no';
		$project->$option_toggle_name = get_input($option_toggle_name, $option_default);
	}
}

// Project membership
$project->membership = ACCESS_PRIVATE;

if ($is_new_project) {
	$project->access_id = ACCESS_PUBLIC;
}

$old_owner_guid = $is_new_project ? 0 : $project->owner_guid;
$new_owner_guid = (int) get_input('owner_guid');

$owner_has_changed = false;
$old_icontime = null;
if (!$is_new_project && $new_owner_guid && $new_owner_guid != $old_owner_guid) {
	// verify new owner is member and old owner/admin is logged in
	if (is_project_member($project_guid, $new_owner_guid) && ($old_owner_guid == $user->guid || $user->isAdmin())) {
		$project->owner_guid = $new_owner_guid;
		$project->container_guid = $new_owner_guid;

		$metadata = elgg_get_metadata(array(
			'guid' => $project_guid,
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

		// @todo Remove this when #4683 fixed
		$owner_has_changed = true;
		$old_icontime = $project->icontime;
	}
}

$must_move_icons = ($owner_has_changed && $old_icontime);

$project->save();

// Invisible project support
// @todo this requires save to be called to create the acl for the project. This
// is an odd requirement and should be removed. Either the acl creation happens
// in the action or the visibility moves to a plugin hook
$visibility = (int)get_input('vis', '', false);
if ($visibility != ACCESS_PUBLIC && $visibility != ACCESS_LOGGED_IN) {
	$visibility = $project->group_acl;
}

if ($project->access_id != $visibility) {
	$project->access_id = $visibility;
}

$project->save();

// project saved so clear sticky form
elgg_clear_sticky_form('projects');

// project creator needs to be member of new project and river entry created
if ($is_new_project) {

	// @todo this should not be necessary...
	elgg_set_page_owner_guid($project->guid);

	$project->join($user);
	add_to_river('river/project/create', 'create', $user->guid, $project->guid, $project->access_id);
}

$has_uploaded_icon = (!empty($_FILES['icon']['type']) && substr_count($_FILES['icon']['type'], 'image/'));

if ($has_uploaded_icon) {

	$icon_sizes = elgg_get_config('projects_icon_sizes');

	$prefix = "projects/" . $project->guid;

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $project->owner_guid;
	$filehandler->setFilename($prefix . ".jpg");
	$filehandler->open("write");
	$filehandler->write(get_uploaded_file('icon'));
	$filehandler->close();
	$filename = $filehandler->getFilenameOnFilestore();

	$sizes = array('tiny', 'small', 'medium', 'large');

	$thumbs = array();
	foreach ($sizes as $size) {
		$thumbs[$size] = get_resized_image_from_existing_file(
			$filename,
			$icon_sizes[$size]['w'],
			$icon_sizes[$size]['h'],
			$icon_sizes[$size]['square']
		);
	}

	if ($thumbs['tiny']) { // just checking if resize successful
		$thumb = new ElggFile();
		$thumb->owner_guid = $project->owner_guid;
		$thumb->setMimeType('image/jpeg');

		foreach ($sizes as $size) {
			$thumb->setFilename("{$prefix}{$size}.jpg");
			$thumb->open("write");
			$thumb->write($thumbs[$size]);
			$thumb->close();
		}

		$project->icontime = time();
	}
}

// @todo Remove this when #4683 fixed
if ($must_move_icons) {
	$filehandler = new ElggFile();
	$filehandler->setFilename('projects');
	$filehandler->owner_guid = $old_owner_guid;
	$old_path = $filehandler->getFilenameOnFilestore();

	$sizes = array('', 'tiny', 'small', 'medium', 'large');

	if ($has_uploaded_icon) {
		// delete those under old owner
		foreach ($sizes as $size) {
			unlink("$old_path/{$project_guid}{$size}.jpg");
		}
	} else {
		// move existing to new owner
		$filehandler->owner_guid = $project->owner_guid;
		$new_path = $filehandler->getFilenameOnFilestore();

		foreach ($sizes as $size) {
			rename("$old_path/{$project_guid}{$size}.jpg", "$new_path/{$project_guid}{$size}.jpg");
		}
	}
}

system_message(elgg_echo("projects:saved"));

forward($project->getUrl());
