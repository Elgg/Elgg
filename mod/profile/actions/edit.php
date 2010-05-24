<?php
/**
 * Elgg profile plugin edit action
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

global $CONFIG;
gatekeeper();

$profile_username = get_input('username');
$profile_owner = get_user_by_username($profile_username);

if (!$profile_owner || !$profile_owner->canEdit()) {
	system_message(elgg_echo("profile:noaccess"));
	forward($_SERVER['HTTP_REFERER']);
}

// grab the defined profile field names and their load the values from POST.
// each field can have its own access, so sort that too.
$input = array();
$accesslevel = get_input('accesslevel');

if (!is_array($accesslevel)) {
	$accesslevel = array();
}

/**
 * wrapper for recursive array walk decoding
 */
function profile_array_decoder(&$v) {
	$v = html_entity_decode($v, ENT_COMPAT, 'UTF-8');
}


foreach($CONFIG->profile as $shortname => $valuetype) {
	// the decoding is a stop gag to prevent &amp;&amp; showing up in profile fields
	// because it is escaped on both input (get_input()) and output (view:output/text). see #561 and #1405.
	// must decode in utf8 or string corruption occurs. see #1567.
	$value = get_input($shortname);
	if (is_array($value)) {
		array_walk_recursive($value, 'profile_array_decoder');
	} else {
		$value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
	}

	// limit to reasonable sizes.
	if (!is_array($value) && $valuetype != 'longtext' && elgg_strlen($value) > 250) {
		$error = sprintf(elgg_echo('profile:field_too_long'), elgg_echo("profile:{$shortname}"));
		register_error($error);
		forward($_SERVER['HTTP_REFERER']);
	}

	if ($valuetype == 'tags') {
		$value = string_to_tag_array($value);
	}

	$input[$shortname] = $value;
}

// display name is handled separately
if ($name = strip_tags(get_input('name'))) {
	if (elgg_strlen($name) > 50) {
		register_error(elgg_echo('user:name:fail'));
	} elseif ($profile_owner->name != $name) {
		$profile_owner->name = $name;
		// @todo this is weird...giving two notifications?
		if ($profile_owner->save()) {
			system_message(elgg_echo('user:name:success'));
		} else {
			register_error(elgg_echo('user:name:fail'));
		}
	}
}

// go through custom fields
if (sizeof($input) > 0) {
	foreach($input as $shortname => $value) {
		remove_metadata($profile_owner->guid, $shortname);
		if (isset($accesslevel[$shortname])) {
			$access_id = (int) $accesslevel[$shortname];
		} else {
			// this should never be executed since the access level should always be set
			$access_id = ACCESS_DEFAULT;
		}
		if (is_array($value)) {
			$i = 0;
			foreach($value as $interval) {
				$i++;
				$multiple = ($i > 1) ? TRUE : FALSE;
				create_metadata($profile_owner->guid, $shortname, $interval, 'text', $profile_owner->guid, $access_id, $multiple);
			}
		} else {
			create_metadata($profile_owner->getGUID(), $shortname, $value, 'text', $profile_owner->getGUID(), $access_id);
		}
	}

	$profile_owner->save();

	// Notify of profile update
	trigger_elgg_event('profileupdate',$user->type,$user);

	//add to river if edited by self
	if (get_loggedin_userid() == $user->guid) {
		add_to_river('river/user/default/profileupdate','update',$_SESSION['user']->guid,$_SESSION['user']->guid,get_default_access($_SESSION['user']));
 	}

	system_message(elgg_echo("profile:saved"));
}

forward($profile_owner->getUrl());
