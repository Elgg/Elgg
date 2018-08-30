<?php
/**
 * Elgg file widget view
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

echo elgg_list_entities([
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $widget->owner_guid,
	'limit' => $num_display,
	'pagination' => false,
	'no_results' => elgg_echo('groups:none'),
]);

$more_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:group:group:member', [
		'username' => $widget->getOwnerEntity()->username,
	]),
	'text' => elgg_echo('groups:more'),
	'is_trusted' => true,
]);
echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $more_link);
