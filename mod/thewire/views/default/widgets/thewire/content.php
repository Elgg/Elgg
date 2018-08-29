<?php
/**
 * User wire post widget display view
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'container_guid' => $widget->owner_guid,
	'limit' => $num_display,
	'pagination' => false,
]);

if (empty($content)) {
	echo elgg_echo('thewire:noposts');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:thewire:owner', [
		'username' => $widget->getOwnerEntity()->username,
	]),
	'text' => elgg_echo('thewire:moreposts'),
	'is_trusted' => true,
]);
echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $more_link);
