<?php
/**
 * Elgg file widget view
 */

$widget = elgg_extract('entity', $vars);

$content = elgg_list_entities_from_relationship([
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $widget->owner_guid,
	'limit' => $widget->num_display,
	'pagination' => false,
]);

if (empty($content)) {
	echo elgg_echo('groups:none');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => 'groups/member/' . $widget->getOwnerEntity()->username,
	'text' => elgg_echo('groups:more'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
