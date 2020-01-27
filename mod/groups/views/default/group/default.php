<?php
/**
 * Group entity view
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggGroup)) {
	return;
}

if (elgg_extract('full_view', $vars, false)) {
	echo elgg_view('groups/profile/summary', $vars);
	return;
}

$members_count = $entity->getMembers(['count' => true]);

$imprint = [
	[
		'icon_name' => 'users',
		'content' => elgg_echo('groups:members_count', [$members_count]),
		'class' => 'elgg-listing-group-members',
	],
];

if (!$entity->isPublicMembership()) {
	$imprint[] = [
		'icon_name' => 'lock',
		'content' => elgg_echo('groups:closed'),
		'class' => 'elgg-listing-group-membership elgg-state elgg-state-danger',
	];
}

$vars['content'] = $entity->briefdescription;
$vars['byline'] = false;
$vars['access'] = false;
$vars['time'] = false;
$vars['imprint'] = $imprint;
$vars['icon_entity'] = $entity;

echo elgg_view('group/elements/summary', $vars);
