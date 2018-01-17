<?php
/**
 * User blog widget display view
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'blog',
	'container_guid' => $widget->owner_guid,
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
]);

if (empty($content)) {
	echo elgg_echo('blog:none');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:blog:owner', [
		'username' => $widget->getOwnerEntity()->username,
	]),
	'text' => elgg_echo('blog:moreblogs'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
