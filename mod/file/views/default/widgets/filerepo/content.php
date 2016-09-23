<?php
/**
 * Elgg file widget view
 */

$widget = elgg_extract('entity', $vars);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'container_guid' => $widget->owner_guid,
	'limit' => $widget->num_display,
	'pagination' => false,
	'distinct' => false,
]);


if (empty($content)) {
	echo elgg_echo('file:none');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => 'file/owner/' . $widget->getOwnerEntity()->username,
	'text' => elgg_echo('file:more'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
