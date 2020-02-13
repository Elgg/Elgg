<?php
/**
 * Livesearch endpoint to search for users who are a member of the provided group
 *
 * @uses $vars['limit']      (int)    number of results to return
 * @uses $vars['term']       (string) search term (for username and displayname)
 * @uses $vars['name']       (string) the input name to be used when submitting the selected value
 * @uses $vars['group_guid'] (int)    the GUID of the group to search members in
 *
 * @throws Elgg\Exceptions\Http\EntityNotFoundException if the group_guid doesn't match a group
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

elgg_gatekeeper();

$limit = (int) elgg_extract('limit', $vars, elgg_get_config('default_limit'));
$query = elgg_extract('term', $vars, elgg_extract('q', $vars));
$input_name = elgg_extract('name', $vars);
$group_guid = (int) elgg_extract('group_guid', $vars);

$group = get_entity($group_guid);
if (!$group instanceof ElggGroup) {
	throw new EntityNotFoundException();
}

$options = [
	'query' => $query,
	'type' => 'user',
	'relationship' => 'member',
	'relationship_guid' => $group_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
	'sort' => 'name',
	'order' => 'ASC',
	'fields' => ['metadata' => ['name', 'username']],
	'item_view' => 'search/entity',
	'input_name' => $input_name,
];

// by default search in all users,
// with 'include_banned' => false, only search in 'allowed' users
if (!(bool) elgg_extract('include_banned', $vars, true)) {
	$options['metadata_name_value_pairs'][] = [
		'name' => 'banned',
		'value' => 'no',
	];
}

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
