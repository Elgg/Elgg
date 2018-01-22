<?php
/**
 * Display the latest related comments
 *
 * Generally used in a sidebar.
 *
 * @uses $vars['subtypes']       Object subtype string or array of subtypes
 * @uses $vars['owner_guid']     The owner of the content being commented on
 * @uses $vars['container_guid'] The container of the content being commented on
 * @uses $vars['limit']          The number of comments to display
 */

$options = [
	'type' => 'object',
	'subtype' => 'comment',
	'limit' => elgg_extract('limit', $vars, 4),
	'wheres' => [],
	'preload_owners' => true,
	'distinct' => false,
];

$owner_guid = elgg_extract('owner_guid', $vars);
$container_guid = elgg_extract('container_guid', $vars);
$subtypes = elgg_extract('subtypes', $vars);

// If owner is defined, view only the comments that have
// been posted on objects owned by that user
if ($owner_guid) {
	$options['wheres'][] = function(\Elgg\Database\QueryBuilder $qb) use ($owner_guid) {
		$qb->joinEntitiesTable('e', 'container_guid', 'inner', 'ce');
		return $qb->compare('ce.owner_guid', '=', $owner_guid, ELGG_VALUE_INTEGER);
	};
}

// If container is defined, view only the comments that have
// been posted on objects placed inside that container
if ($container_guid) {
	$options['wheres'][] = function(\Elgg\Database\QueryBuilder $qb) use ($container_guid) {
		$qb->joinEntitiesTable('e', 'container_guid', 'inner', 'ce');
		return $qb->compare('ce.container_guid', '=', $container_guid, ELGG_VALUE_INTEGER);
	};
}

// If subtypes are defined, view only the comments that have been
// posted on objects that belong to any of those subtypes
if ($subtypes) {
	$options['wheres'][] = function(\Elgg\Database\QueryBuilder $qb) use ($subtypes) {
		$qb->joinEntitiesTable('e', 'container_guid', 'inner', 'ce');
		return $qb->compare('ce.subtype', 'IN', $subtypes, ELGG_VALUE_STRING);
	};
}

$title = elgg_echo('generic_comments:latest');
$comments = elgg_get_entities($options);
if ($comments) {
	$body = elgg_view('page/components/list', [
		'items' => $comments,
		'pagination' => false,
		'list_class' => 'elgg-latest-comments',
		'full_view' => false,
	]);
} else {
	$body = '<p>' . elgg_echo('generic_comment:none') . '</p>';
}

echo elgg_view_module('aside', $title, $body);
