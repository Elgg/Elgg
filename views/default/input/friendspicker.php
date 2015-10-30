<?php
/**
 * Friendspicker input
 *
 * @uses $vars['user'] User entity
 * @uses $vars['name'] Name of the input. Default: 'friend'
 * @uses $vars['value'] An array of guids or entities to be checked
 * @uses $vars['entities'] An array of ElggUser entities to populate the picker with. Defaults to current user's friends
 */
$name = elgg_extract('name', $vars, 'friend');
$value = (array) elgg_extract('value', $vars, []);
array_walk($value, function(&$elm) {
	// normalize to guids
	$elm = $elm instanceof ElggEntity ? $elm->guid : (int) $elm;
});

$list_options = [
	'input_name' => $name,
	'input_values' => $value,
	'list_class' => 'elgg-friendspicker-list',
	'item_view' => 'input/friendspicker/item',
	'no_results' => elgg_echo('friendspicker:no_results'),
];

$user = elgg_extract('user', $vars, elgg_get_logged_in_user_entity(), false);
/* @var $user ElggUser */

$entities = elgg_extract('entities', $vars);

if (empty($entities) && empty($user)) {
	return;
}

if (is_array($entities)) {
	array_filter($entities, function($e) {
		return $e instanceof ElggEntity;
	});
	usort($entities, function($e1, $e2) {
		return strcmp($e1->getDisplayName(), $e2->getDisplayName());
	});
	
	$body = elgg_view_entity_list($entities, $list_options);
	$count = count($entities);
} else {
	$count = $user->getFriends([
		'count' => true,
	]);
	
	$dbprefix = elgg_get_config('dbprefix');
	$options = [
		'types' => 'user',
		'limit' => 0,
		'joins' => ["JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"],
		'order_by' => 'ue.name',
		'relationship' => 'friend',
		'relationship_guid' => $user->guid,
	];
	
	$options = array_merge($options, $list_options);
	$body = elgg_list_entities_from_relationship($options);
}

if (!$count) {
	echo $body;
	return;
}

$filter = elgg_view('input/text', [
	'placeholder' => elgg_echo('friendspicker:filter'),
	'class' => 'elgg-friendspicker-filter',
]);

$header = elgg_format_element('div', [
	'class' => 'elgg-friendspicker-header',
], $filter);

$checkbox = elgg_view('input/checkbox', [
	'class' => 'elgg-friendspicker-toggle',
]);
$footer = elgg_format_element('label', [
	'class' => 'elgg-friendspicker-footer',
], $checkbox . elgg_echo('friendspicker:toggle'));

echo elgg_format_element('div', [
	'class' => 'elgg-input-friendspicker',
], $header . $body . $footer);
?>
<script>
	require(['input/friendspicker']);
</script>


