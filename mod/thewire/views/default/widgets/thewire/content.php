<?php
/**
 * User wire post widget display view
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	$options['owner_guid'] = $owner->guid;
} else {
	$options['container_guid'] = $widget->owner_guid;
}

$content = elgg_list_entities($options);
if (empty($content)) {
	echo elgg_echo('thewire:noposts');
	return;
}

echo $content;

if (!$owner instanceof \ElggUser) {
	return;
}

$more_link = elgg_view('output/url', [
	'text' => elgg_echo('thewire:moreposts'),
	'href' => elgg_generate_url('collection:object:thewire:owner', [
		'username' => $owner->username,
	]),
	'is_trusted' => true,
]);
echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $more_link);
