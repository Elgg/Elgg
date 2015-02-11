<?php

/**
 * Group view for an invitation request
 *
 * @uses $vars['entity'] Group entity
 */

$user = elgg_get_logged_in_user_entity();
$group = elgg_extract('entity', $vars);

if (!elgg_instanceof($group, 'group')) {
	return true;
}

$icon = elgg_view_entity_icon($group, 'small');

$url = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}");
$accept_button = elgg_view('output/url', array(
	'href' => $url,
	'text' => elgg_echo('accept'),
	'class' => 'elgg-button elgg-button-submit',
	'is_trusted' => true,
		));

$url = "action/groups/killinvitation?user_guid={$user->getGUID()}&group_guid={$group->getGUID()}";
$delete_button = elgg_view('output/url', array(
	'href' => $url,
	'confirm' => elgg_echo('groups:invite:remove:check'),
	'text' => elgg_echo('delete'),
	'class' => 'elgg-button elgg-button-delete mlm',
		));

$alt = $accept_button . $delete_button;

$summary = elgg_view('group/elements/summary', array(
	'entity' => $group,
	'subtitle' => $group->briefdescription,
));

echo elgg_view_image_block($icon, $summary, array('image_alt' => $alt));
