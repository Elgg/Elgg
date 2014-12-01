<?php
/**
 * Elgg bookmarks widget
 *
 * @package Bookmarks
 */

$widget = $vars['entity'];
/* @var ElggWidget $widget */

$max = (int) $widget->num_display;

$options = array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guid' => $widget->owner_guid,
	'limit' => $max,
	'full_view' => FALSE,
	'pagination' => FALSE,
	'distinct' => false,
);
$content = elgg_list_entities($options);

echo $content;

if (!$content) {
    echo elgg_echo('bookmarks:none');
    return;
}

$owner = $widget->getOwnerEntity();
if ($owner instanceof ElggGroup) {
    $url = "bookmarks/group/{$owner->guid}/all";
} else {
    $url = "bookmarks/owner/{$owner->username}";
}

$more_link = elgg_view('output/url', array(
    'href' => $url,
    'text' => elgg_echo('more'),
    'is_trusted' => true,
));
echo "<span class=\"elgg-widget-more\">$more_link</span>";
