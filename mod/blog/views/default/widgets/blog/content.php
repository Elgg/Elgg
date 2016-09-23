<?php
/**
 * User blog widget display view
 */

$widget = elgg_extract('entity', $vars);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'blog',
	'container_guid' => $widget->owner_guid,
	'limit' => $widget->num_display,
	'pagination' => false,
	'distinct' => false,
]);

if (empty($content)) {
	echo elgg_echo('blog:noblogs');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => 'blog/owner/' . $widget->getOwnerEntity()->username,
	'text' => elgg_echo('blog:moreblogs'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
