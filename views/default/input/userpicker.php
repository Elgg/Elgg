<?php
/**
 * User Picker.  Sends an array of user guids.
 *
 * @uses $vars['values'] Array of user guids for already selected users or null
 * @uses $vars['limit'] Limit number of users (default 0 = no limit)
 * @uses $vars['name'] Name of the returned data array (default "members")
 * @uses $vars['handler'] Name of page handler used to power search (default "livesearch")
 * @uses $vars['options'] Additional options to pass to the handler with the URL query
 *                        If using custom options, make sure to impose a signed request gatekeeper in the resource view
 * @uses $vars['only_friends'] If enabled, will turn the input into a friends picker (default: false)
 * @uses $vars['show_friends'] Show the option to limit the search to friends (default: true)
 * @uses $vars['include_banned'] Include banned users in the search results (default: false)
 *
 * Defaults to lazy load user lists in alphabetical order. User needs
 * to type two characters before seeing the user popup list.
 *
 * As users are selected they move down to a "users" box.
 * When this happens, a hidden input is created to return the GUID in the array with the form
 */

$name = elgg_extract('name', $vars, 'members', false);

$guids = (array) elgg_extract('values', $vars, elgg_extract('value', $vars, []));

$params = elgg_extract('options', $vars, []);

$friends_only = (bool) elgg_extract('only_friends', $vars, false);
$show_friends = (bool) elgg_extract('show_friends', $vars, !$friends_only);
if ($friends_only) {
	$params['friends_only'] = true;
}

$params['include_banned'] = (bool) elgg_extract('include_banned', $vars, false);

if (!empty($params)) {
	ksort($params);

	// We sign custom parameters, so that plugins can validate
	// that the request is unaltered, if needed
	$mac = elgg_build_hmac($params);
	$params['mac'] = $mac->getToken();
}

$handler = elgg_extract('handler', $vars, 'livesearch');
$params['view'] = 'json'; // force json viewtype

$wrapper_options = [
	'class' => elgg_extract_class($vars, ['elgg-user-picker']),
	'data-limit' => (int) elgg_extract('limit', $vars, 0),
	'data-name' => $name,
	'data-handler' => elgg_http_add_url_query_elements($handler, $params),
];

$picker = elgg_format_element('input', [
	'type' => 'text',
	'class' => 'elgg-input-user-picker',
	'size' => 30,
]);

$picker .= elgg_view('input/hidden', ['name' => $name]);
	
if ($show_friends) {
	$picker .= elgg_view('input/checkbox', [
		'name' => 'match_on',
		'value' => 'friends',
		'default' => elgg_extract('match_on', $vars, 'users', false),
		'label' => elgg_echo('userpicker:only_friends'),
	]);
} elseif ($friends_only) {
	$wrapper_options['data-match-on'] = 'friends';
} else {
	$wrapper_options['data-match-on'] = elgg_extract('match_on', $vars, 'users', false);
}

$items = '';
foreach ($guids as $guid) {
	$entity = get_entity($guid);
	if ($entity) {
		$items .= elgg_view('input/autocomplete/item', [
			'entity' => $entity,
			'input_name' => $name,
		]);
	}
}

$picker .= elgg_format_element('ul', ['class' => 'elgg-list elgg-user-picker-list'], $items);

echo elgg_format_element('div', $wrapper_options, $picker);

?>
<script>
	require(['elgg/UserPicker'], function (UserPicker) {
		var name = <?= json_encode($name) ?>;
		UserPicker.setup('.elgg-user-picker[data-name="' + name + '"]');
	});
</script>
