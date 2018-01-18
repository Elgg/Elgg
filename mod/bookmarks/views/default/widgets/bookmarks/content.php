<?php
/**
 * Elgg bookmarks widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guid' => $widget->owner_guid,
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
]);

if (empty($content)) {
	echo elgg_echo('bookmarks:none');
	return;
}

echo $content;

$owner = $widget->getOwnerEntity();
if ($owner instanceof ElggGroup) {
	$url = elgg_generate_url('collection:object:bookmarks:group', ['guid' => $owner->guid]);
} else {
	$url = elgg_generate_url('collection:object:bookmarks:owner', ['username' => $owner->username]);
}

$more_link = elgg_view('output/url', [
	'href' => $url,
	'text' => elgg_echo('more'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
