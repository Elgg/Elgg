<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggGroup)) {
	return;
}

// number of members
$content = $entity->getMembers(['count' => true]) . ' ' . elgg_echo('groups:member');

echo elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('icon_name', $vars, 'users'),
	'content' => $content,
	'class' => 'elgg-listing-group-members',
]);
