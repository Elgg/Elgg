<?php
/**
 * User view of a livesearch result when inviting a user for a group
 *
 * @uses $vars['entity']     the matched user for the search query
 * @uses $vars['group_guid'] when present will show if the user is already a member of the group
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

// group guid can isn't passed through the original livesearch endpoint resource
$vars['group_guid'] = (int) elgg_extract('group_guid', $vars, (int) get_input('group_guid'));

// reset the viewtype so we can render html views
$viewtype = elgg_get_viewtype();
elgg_set_viewtype('default');

$icon = elgg_view_entity_icon($entity, 'tiny', [
	'use_link' => false,
	'href' => false,
	'use_hover' => false,
]);

$title_text = $entity->getDisplayName();

$group = get_entity($vars['group_guid']);
if ($group instanceof ElggGroup) {
	if ($group->isMember($entity)) {
		$title_text .= elgg_format_element('span', ['class' => ['mls', 'elgg-subtext']], elgg_echo('groups:invite:member'));
	} elseif (check_entity_relationship($group->guid, 'invited', $entity->guid)) {
		$title_text .= elgg_format_element('span', ['class' => ['mls', 'elgg-subtext']], elgg_echo('groups:invite:invited'));
	}
}

$title = elgg_format_element('h3', [], $title_text);

$label = elgg_view_image_block($icon, $title, [
	'class' => 'elgg-autocomplete-item',
]);

$data = $entity->toObject();
$data->label = $label;
$data->value = $entity->username;
$data->icon = $icon;

if (elgg_extract('input_name', $vars)) {
	$data->html = elgg_view('groups/invite/user_html', $vars);
}

echo json_encode($data);

// restore viewtype
elgg_set_viewtype($viewtype);
