<?php
/**
 * Elgg profile edit action
 *
 */

elgg_make_sticky_form('profile:edit');

$guid = get_input('guid');
$owner = get_entity($guid);

if (!($owner instanceof ElggUser) || !$owner->canEdit()) {
	return elgg_error_response(elgg_echo('profile:noaccess'));
}

// grab the defined profile field names and their load the values from POST.
// each field can have its own access, so sort that too.
$input = [];
$accesslevel = get_input('accesslevel');

if (!is_array($accesslevel)) {
	$accesslevel = [];
}

$profile_fields = elgg()->fields->get('user', 'user');
foreach ($profile_fields as $field) {
	$shortname = $field['name'];
	$valuetype = $field['#type'];
	
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
		return elgg_error_response(elgg_echo('profile:invalid_email', [elgg_echo("profile:{$shortname}")]));
	}
	
	$input[$shortname] = $value;
}

// display name is handled separately
$name = strip_tags(get_input('name'));
if ($name) {
	if (elgg_strlen($name) > 50) {
		return elgg_error_response(elgg_echo('user:name:fail'));
	} elseif ($owner->name !== $name) {
		$owner->name = $name;
	}
}

if (empty($input)) {
	return elgg_ok_response('', '', $owner->getUrl());
}

// go through custom fields
// fetch default access level for the user for use in fallback cases
$user_default_access = get_default_access($owner);

foreach ($input as $shortname => $value) {
	// get field access
	$access_id = (int) elgg_extract($shortname, $accesslevel, $user_default_access);
	
	// store data
	$owner->setProfileData($shortname, $value, $access_id);
}

$owner->save();

// Notify of profile update
elgg_trigger_event('profileupdate', $owner->type, $owner);

elgg_clear_sticky_form('profile:edit');

return elgg_ok_response('', elgg_echo("profile:saved"), $owner->getUrl());
