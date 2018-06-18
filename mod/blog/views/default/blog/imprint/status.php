<?php
elgg_deprecated_notice('The view blog/imprint/status is no longer used', '3.0');

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggBlog) {
	return;
}

if ($entity->status == 'published' || empty($entity->status)) {
	return;
}
$icon = elgg_view_icon('warning');
$status_text = $icon . elgg_echo("status:{$entity->status}");

echo elgg_format_element('span', [
	'class' => 'elgg-listing-blog-status',
], $status_text);
