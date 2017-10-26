<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggGroup)) {
	return;
}

if ($entity->isPublicMembership()) {
	$content = elgg_echo('groups:open');
	$icon_name = 'lock';
} else {
	$content = elgg_echo('groups:closed');
	$icon_name = 'unlock-alt';
}

echo elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('icon_name', $vars, $icon_name),
	'content' => $content,
	'class' => 'elgg-listing-group-membership',
]);
