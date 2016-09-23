<?php
/**
 * User wire post widget display view
 */

$widget = elgg_extract('entity', $vars);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'container_guid' => $widget->owner_guid,
	'limit' => $widget->num_display,
	'pagination' => false,
]);

if (empty($content)) {
	echo elgg_echo('thewire:noposts');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => "thewire/owner/" . $widget->getOwnerEntity()->username,
	'text' => elgg_echo('thewire:moreposts'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
