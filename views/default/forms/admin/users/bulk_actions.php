<?php
/**
 * Form wrapper for bulk actions on a user listing
 *
 * @uses $vars['buttons'] An array of bulk action buttons (these should set the 'formaction' attribute to point to the correct action)
 * @uses $vars['filter']  An indication of what the listing is used for (eg all, online, admin, etc.) (default: all)
 * @uses $vars['options'] Additional options for the user selection
 */

elgg_require_css('forms/admin/users/bulk_actions');
elgg_require_js('forms/admin/users/bulk_actions');

// did we search
$query = get_input('q');
$getter = $query ? 'elgg_search' : 'elgg_get_entities';

// make selection options
$default_options = [
	'type' => 'user',
	'subtype'=> null,
	'full_view' => false,
	'list_type' => 'table',
	'limit' => max(25, elgg_get_config('default_limit'), (int) get_input('limit', 0)),
	'columns' => [
		elgg()->table_columns->checkbox(elgg_view('input/checkbox', [
			'name' => 'user_guids',
			'title' => elgg_echo('table_columns:fromView:checkbox'),
		]), [
			'name' => 'user_guids[]',
		]),
		elgg()->table_columns->icon(null, [
			'use_hover' => false,
		]),
		elgg()->table_columns->user(null, [
			'item_view' => 'user/default/admin_column',
		]),
		elgg()->table_columns->email(),
		elgg()->table_columns->time_created(null, [
			'format' => 'friendly',
		]),
		elgg()->table_columns->entity_menu(null, [
			'add_user_hover_admin_section' => true,
			'admin_listing' => elgg_extract('filter', $vars, 'all'),
		]),
	],
	'list_class' => 'elgg-admin-users',
	'query' => $query,
];

// merge additional options
$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($default_options, $options);

$users = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($options, $getter) {
	return elgg_list_entities($options, $getter);
});
if (empty($users)) {
	$no_results = elgg_extract('no_results', $vars);
	if (empty($no_results) || $no_results === true) {
		$no_results = elgg_echo('notfound');
	}
	
	echo elgg_view('page/components/no_results', ['no_results' => $no_results]);
	return;
}

$buttons = elgg_extract('buttons', $vars);
if (!empty($buttons)) {
	foreach ($buttons as &$button) {
		$button['disabled'] = true;
	}
	
	echo elgg_view_field([
		'#type' => 'fieldset',
		'#class' => ['elgg-admin-users-bulkactions-buttons', 'mbs'],
		'align' => 'horizontal',
		'fields' => $buttons,
	]);
}

echo $users;
