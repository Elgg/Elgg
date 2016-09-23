<?php
/**
 * Pages widget
 */

$widget = elgg_extract('entity', $vars);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page_top',
	'container_guid' => $widget->owner_guid,
	'limit' => $widget->pages_num,
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
echo "<div class=\"elgg-widget-more\">$more_link</div>";
