<?php

/**
 * Group edit form
 *
 * This view contains everything related to group access.
 * eg: how can people join this group, who can see the group, etc
 *
 * @package ElggGroups
 */
$entity = elgg_extract('entity', $vars, false);
$membership = elgg_extract('membership', $vars);
$visibility = elgg_extract('vis', $vars);
$owner_guid = elgg_extract('owner_guid', $vars);
$content_access_mode = elgg_extract('content_access_mode', $vars);

echo elgg_view_input('select', [
	'name' => 'membership',
	'id' => 'groups-membership',
	'value' => $membership,
	'options_values' => [
		ACCESS_PRIVATE => elgg_echo('groups:access:private'),
		ACCESS_PUBLIC => elgg_echo('groups:access:public'),
	],
	'label' => elgg_echo('groups:membership'),
]);

if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {

	$visibility_options = [
		ACCESS_PRIVATE => elgg_echo('groups:access:group'),
		ACCESS_LOGGED_IN => elgg_echo('LOGGED_IN'),
		ACCESS_PUBLIC => elgg_echo('PUBLIC'),
	];
	if (elgg_get_config('walled_garden')) {
		unset($visibility_options[ACCESS_PUBLIC]);
	}

	echo elgg_view_input('access', [
		'name' => 'vis',
		'id' => 'groups-vis',
		'value' => $visibility,
		'options_values' => $visibility_options,
		'entity' => $entity,
		'entity_type' => 'group',
		'entity_subtype' => '',
		'label' => elgg_echo('groups:visibility'),
	]);
}

echo elgg_view_input('select', [
	'name' => 'content_access_mode',
	'id' => 'groups-content-access-mode',
	'value' => $content_access_mode,
	'options_values' => [
		ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED => elgg_echo('groups:content_access_mode:unrestricted'),
		ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY => elgg_echo('groups:content_access_mode:membersonly'),
	],
	'label' => elgg_echo('groups:content_access_mode'),
	// Warn the user that changing the content access mode to more
	// restrictive will not affect the existing group content
	'help' => $entity && $entity->getContentAccessMode() == ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED ? elgg_echo('groups:content_access_mode:warning') : '',
	// Disable content_access_mode field for hidden groups because the setting
	// will be forced to members_only regardless of the entered value
	'disabled' => $entity && $entity->access_id === $entity->group_acl,
]);

if ($entity && ($owner_guid == elgg_get_logged_in_user_guid() || elgg_is_admin_logged_in())) {
	$members = [];

	$dbprefix = elgg_get_config('dbprefix');
	$options = [
		'type' => 'user',
		'relationship' => 'member',
		'relationship_guid' => $entity->getGUID(),
		'inverse_relationship' => true,
		'limit' => false,
		'callback' => false,
		'joins' => ["JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"],
		'selects' => ['ue.*'],
		'order_by' => 'ue.name ASC',
	];

	$batch = new ElggBatch('elgg_get_entities_from_relationship', $options);
	foreach ($batch as $member) {
		$option_text = "$member->name (@$member->username)";
		$members[$member->guid] = htmlspecialchars($option_text, ENT_QUOTES, 'UTF-8', false);
	}

	echo elgg_view_input('select', [
		'name' => 'owner_guid',
		'id' => 'groups-owner-guid',
		'value' => $owner_guid,
		'options_values' => $members,
		'class' => 'groups-owner-input',
		'label' => elgg_echo('groups:owner'),
		'help' => $owner_guid == elgg_get_logged_in_user_guid() ? elgg_echo('groups:owner:warning') : '',
	]);
}