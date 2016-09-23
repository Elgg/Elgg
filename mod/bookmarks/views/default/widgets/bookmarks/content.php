<?php
/**
 * Elgg bookmarks widget
 */

$widget = elgg_extract('entity', $vars);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guid' => $widget->owner_guid,
	'limit' => $widget->num_display,
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
    $url = "bookmarks/group/{$owner->guid}/all";
} else {
    $url = "bookmarks/owner/{$owner->username}";
}

$more_link = elgg_view('output/url', [
    'href' => $url,
    'text' => elgg_echo('more'),
    'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
