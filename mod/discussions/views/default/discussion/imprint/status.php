<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggDiscussion) {
	return;
}

if ($entity->status == 'open') {
	return;
}

$icon = elgg_view_icon('lock');
$status_text = $icon . elgg_echo("status:{$entity->status}");

echo elgg_format_element('span', [
	'class' => 'elgg-listing-discussion-status',
], $status_text);