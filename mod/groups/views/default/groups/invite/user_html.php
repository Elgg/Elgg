<?php
/**
 * Group invite user HTML view for autocomplete items
 *
 * @uses $vars['entity']     the selected entity
 * @uses $vars['input_name'] name of the returned data array
 * @uses $vars['group_guid'] when present will show if the user is already a member of the group
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

$input_name = elgg_extract('input_name', $vars);
if (empty($input_name)) {
	return;
}

$icon = elgg_view_entity_icon($entity, 'tiny', ['use_hover' => false]);
$delete_icon = elgg_view_icon('delete', ['class' => 'elgg-autocomplete-item-remove']);

$title = $entity->getDisplayName();

$group = get_entity(elgg_extract('group_guid', $vars));
if ($group instanceof ElggGroup) {
	if ($group->isMember($entity)) {
		$title .= elgg_format_element('span', ['class' => ['mls', 'elgg-subtext']], elgg_echo('groups:invite:member'));
	} elseif (check_entity_relationship($group->guid, 'invited', $entity->guid)) {
		$title .= elgg_format_element('span', ['class' => ['mls', 'elgg-subtext']], elgg_echo('groups:invite:invited'));
	}
}

$body = elgg_view_image_block($icon, $title, ['image_alt' => $delete_icon]);
$body .= elgg_view('input/hidden', [
	'name' => "{$input_name}[]",
	'value' => $entity->guid,
]);

echo elgg_format_element('li', [
	'class' => 'elgg-item',
	'data-guid' => $entity->guid,
], $body);
