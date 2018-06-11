<?php
/**
 * Livesearch endpoint to search for users who are a member of the provided group
 *
 * @uses get_input('limit')      (int)    number of results to return
 * @uses get_input('term')       (string) search term (for username and displayname)
 * @uses get_input('name')       (string) the input name to be used when submitting the selected value
 * @uses get_input('group_guid') (int)    the GUID of the group to search members in
 *
 * @throws Elgg\EntityNotFoundException if the group_guid doesn't match a group
 */

use Elgg\EntityNotFoundException;

elgg_gatekeeper();

$limit = get_input('limit', elgg_get_config('default_limit'));
$query = get_input('term', get_input('q'));
$input_name = get_input('name');
$group_guid = (int) get_input('group_guid');

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

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
