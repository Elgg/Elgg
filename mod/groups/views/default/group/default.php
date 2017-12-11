<?php
/**
 * Group entity view
 *
 * @package ElggGroups
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggGroup)) {
	return;
}

if (elgg_extract('full_view', $vars, false)) {
	echo elgg_view('groups/profile/summary', $vars);
	return;
}

$icon = elgg_view_entity_icon($entity, 'small', $vars);

$vars['content'] = $entity->briefdescription;
$vars['handler'] = 'groups';

$list_body = elgg_view('group/elements/summary', $vars);

echo elgg_view_image_block($icon, $list_body, $vars);
