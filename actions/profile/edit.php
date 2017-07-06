<?php
/**
 * Elgg profile edit action
 *
 */

elgg_make_sticky_form('profile:edit');

$guid = get_input('guid');
$owner = get_entity($guid);

if (!$owner || !($owner instanceof ElggUser) || !$owner->canEdit()) {
	register_error(elgg_echo('profile:noaccess'));
	forward(REFERER);
}

// grab the defined profile field names and their load the values from POST.
// each field can have its own access, so sort that too.
$input = [];
$accesslevel = get_input('accesslevel');

if (!is_array($accesslevel)) {
	$accesslevel = [];
}

$profile_fields = elgg_get_config('profile_fields');
foreach ($profile_fields as $shortname => $valuetype) {
	$value = get_input($shortname);
	
	if ($value === null) {
		// only submitted profile fields should be updated
		continue;
	}
	
	// the decoding is a stop gap to prevent &amp;&amp; showing up in profile fields
	// because it is escaped on both input (get_input()) and output (view:output/text). see #561 and #1405.
	// must decode in utf8 or string corruption occurs. see #1567.
	if (is_array($value)) {
		array_walk_recursive($value, function(&$v) {
			$v = elgg_html_decode($v);
		});
	} else {
		$value = elgg_html_decode($value);
	}
	
	// convert tags fields to array values
	if ($valuetype == 'tags') {
		$value = string_to_tag_array($value);
	}

	if ($value && $valuetype == 'url' && !preg_match('~^https?\://~i', $value)) {
		$value = "http://$value";
	}

	if ($valuetype == 'email' && !empty($value) && !is_email_address($value)) {
		register_error(elgg_echo('profile:invalid_email', [
			elgg_echo("profile:{$shortname}")
		]));
		forward(REFERER);
	}
	
	$input[$shortname] = $value;
}

// display name is handled separately
$name = strip_tags(get_input('name'));
if ($name) {
	if (elgg_strlen($name) > 50) {
		register_error(elgg_echo('user:name:fail'));
	} elseif ($owner->name != $name) {
		$owner->name = $name;
		$owner->save();
	}
}

// go through custom fields
if (sizeof($input) > 0) {
	// fetch default access level for the user for use in fallback cases
	$user_default_access = get_default_access($owner);
	
	foreach ($input as $shortname => $value) {
		$owner->deleteAnnotations("profile:$shortname");

		// for BC, keep storing fields in MD, but we'll read annotations only
		$options = [
			'guid' => $owner->guid,
			'metadata_name' => $shortname,
			'limit' => false
		];
		elgg_delete_metadata($options);
		
		if (!is_null($value) && ($value !== '')) {
			// only create metadata for non empty values (0 is allowed) to prevent metadata records
			// with empty string values #4858
			
			if (isset($accesslevel[$shortname])) {
				$access_id = (int) $accesslevel[$shortname];
			} else {
				// this should never be executed since the access level should always be set
				$access_id = $user_default_access;
			}

			if (!is_array($value)) {
				$value = [$value];
			}
			foreach ($value as $interval) {
				create_annotation($owner->guid, "profile:$shortname", $interval, 'text', $owner->guid, $access_id);
			}

			// for BC, keep storing fields in MD, but we'll read annotations only
			foreach (array_values($value) as $i => $interval) {
				$multiple = ($i > 0);
				create_metadata($owner->guid, $shortname, $interval, 'text', $owner->guid, null, $multiple);
			}
		}
	}

	$owner->save();

	// Notify of profile update
	elgg_trigger_event('profileupdate', $owner->type, $owner);

	elgg_clear_sticky_form('profile:edit');
	system_message(elgg_echo("profile:saved"));
}

forward($owner->getUrl());
