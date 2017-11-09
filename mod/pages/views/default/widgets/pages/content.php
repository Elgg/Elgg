<?php
/**
 * Pages widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->pages_num ?: 5;

$content = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => 'page',
	'container_guid' => $widget->owner_guid,
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'limit' => $num_display,
	'pagination' => false,
]);

if (empty($content)) {
	echo elgg_echo('pages:none');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => 'pages/owner/' . $widget->getOwnerEntity()->username,
	'text' => elgg_echo('pages:more'),
	'is_trusted' => true,
]);

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $more_link);
