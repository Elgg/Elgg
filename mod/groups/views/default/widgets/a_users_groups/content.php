<?php
/**
 * Group membership widget
 */

$widget = elgg_extract('entity', $vars);
$owner = $widget->getOwnerEntity();

$num_display = (int) $widget->num_display ?: 4;

$more_link = elgg_view_url(elgg_generate_url('collection:group:group:member', ['username' => $owner->username]), elgg_echo('groups:more'));

echo elgg_list_entities([
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $widget->owner_guid,
	'limit' => $num_display,
	'pagination' => false,
	'no_results' => elgg_echo('groups:none'),
	'widget_more' => $more_link,
]);
