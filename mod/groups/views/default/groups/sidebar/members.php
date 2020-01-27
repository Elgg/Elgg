<?php
use Elgg\Database\Clauses\OrderByClause;

/**
 * Group members sidebar
 *
 * @uses $vars['entity'] Group entity
 * @uses $vars['limit']  The number of members to display
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggGroup)) {
	return;
}

$limit = elgg_extract('limit', $vars, 14);

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:user:user:group_members', [
		'guid' => $entity->guid,
	]),
	'text' => elgg_echo('groups:members:more'),
	'is_trusted' => true,
]);

$body = elgg_list_entities([
	'relationship' => 'member',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'type' => 'user',
	'limit' => $limit,
	'order_by' => [
		new OrderByClause('r.time_created', 'DESC'),
	],
	'pagination' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'elgg-gallery-users',
]);

$body .= "<div class='center mts'>$all_link</div>";

$count = $entity->getMembers(['count' => true]);

echo elgg_view_module('aside', elgg_echo('groups:members') . " ({$count})", $body);
