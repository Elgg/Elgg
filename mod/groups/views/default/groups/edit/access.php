<?php
/**
 * Group edit form
 *
 * This view contains everything related to group access.
 * eg: how can people join this group, who can see the group, etc
 */

$entity = elgg_extract('entity', $vars, false);
$membership = elgg_extract('membership', $vars);
$visibility = elgg_extract('vis', $vars);
$owner_guid = elgg_extract('owner_guid', $vars);
$content_access_mode = elgg_extract('content_access_mode', $vars);
$show_content_default_access = (bool) elgg_extract('show_content_default_access', $vars, true);
$content_default_access = elgg_extract('content_default_access', $vars);
$show_group_owner_transfer = (bool) elgg_extract('show_group_owner_transfer', $vars, true);

// group membership
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('groups:membership'),
	'name' => 'membership',
	'id' => 'groups-membership',
	'value' => $membership,
	'options_values' => [
		ACCESS_PRIVATE => elgg_echo('groups:access:private'),
		ACCESS_PUBLIC => elgg_echo('groups:access:public'),
	],
]);

// group access (hidden groups)
if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {
	$visibility_options = [
		ACCESS_PRIVATE => elgg_echo('groups:access:group'),
		ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
		ACCESS_PUBLIC => elgg_echo('access:label:public'),
	];
	if (elgg_get_config('walled_garden')) {
		unset($visibility_options[ACCESS_PUBLIC]);
	}
		
	echo elgg_view_field([
		'#type' => 'access',
		'#label' => elgg_echo('groups:visibility'),
		'name' => 'vis',
		'id' => 'groups-vis',
		'value' => $visibility,
		'options_values' => $visibility_options,
		'entity' => $entity,
		'entity_type' => 'group',
		'entity_subtype' => '',
	]);
}

// group content access mode
$access_mode_params = [
	'#type' => 'select',
	'#label' => elgg_echo('groups:content_access_mode'),
	'name' => 'content_access_mode',
	'id' => 'groups-content-access-mode',
	'value' => $content_access_mode,
	'options_values' => [
		ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED => elgg_echo('groups:content_access_mode:unrestricted'),
		ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY => elgg_echo('groups:content_access_mode:membersonly'),
	],
];

if ($entity instanceof \ElggGroup) {
	// Disable content_access_mode field for hidden groups because the setting
	// will be forced to members_only regardless of the entered value
	$acl = $entity->getOwnedAccessCollection('group_acl');
	if ($acl instanceof ElggAccessCollection && ($entity->access_id === $acl->id)) {
		$access_mode_params['disabled'] = 'disabled';
	}
	
	if ($entity->getContentAccessMode() == ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED) {
		// Warn the user that changing the content access mode to more
		// restrictive will not affect the existing group content
		$access_mode_params['#help'] = elgg_echo('groups:content_access_mode:warning');
	}
}

echo elgg_view_field($access_mode_params);

// group default access
if ($show_content_default_access) {
	$content_default_access_options = [
		'' => elgg_echo('groups:content_default_access:not_configured'),
		ACCESS_PRIVATE => elgg_echo('groups:access:group'),
		ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
	];
	if (!elgg_get_config('walled_garden')) {
		$content_default_access_options[ACCESS_PUBLIC] = elgg_echo('access:label:public');
	}
	
	echo elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('groups:content_default_access'),
		'#help' => elgg_echo('groups:content_default_access:help'),
		'name' => 'content_default_access',
		'value' => $content_default_access,
		'options_values' => $content_default_access_options,
	]);
}

// group owner transfer
if ($show_group_owner_transfer && $entity && ($owner_guid == elgg_get_logged_in_user_guid() || elgg_is_admin_logged_in())) {
	$owner_guid_options = [
		'#type' => 'userpicker',
		'#label' => elgg_echo('groups:owner'),
		'name' => 'owner_guid',
		'value' =>  $owner_guid,
		'limit' => 1,
		'match_on' => 'group_members',
		'show_friends' => false,
		'options' => [
			'group_guid' => $entity->guid,
		],
	];
	
	if ($owner_guid == elgg_get_logged_in_user_guid()) {
		$owner_guid_options['#help'] = elgg_echo('groups:owner:warning');
	}
	
	echo elgg_view_field($owner_guid_options);
}
