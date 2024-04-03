<?php
use Elgg\Database\Clauses\OrderByClause;

/**
 * Group members sidebar
 *
 * @uses $vars['entity'] Group entity
 * @uses $vars['limit']  The number of members to display
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggGroup) {
	return;
}

$count = $entity->getMembers(['count' => true]);
if (empty($count)) {
	return;
}

$body = elgg_list_entities([
	'relationship' => 'member',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'type' => 'user',
	'limit' => elgg_extract('limit', $vars, 14),
	'order_by' => [
		new OrderByClause('r.time_created', 'DESC'),
	],
	'pagination' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'elgg-gallery-users',
]);

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:user:user:group_members', [
		'guid' => $entity->guid,
	]),
	'text' => elgg_echo('groups:members:more'),
	'is_trusted' => true,
]);

$body .= elgg_format_element('div', ['class' => ['center', 'mts']], $all_link);

echo elgg_view_module('aside', elgg_echo('groups:members') . " ({$count})", $body);
