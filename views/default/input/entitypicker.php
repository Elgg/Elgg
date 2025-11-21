<?php
/**
 * Entity Picker. Sends an array of entity guids.
 *
 * @uses $vars['values']         Array of user guids for already selected entities or null
 * @uses $vars['limit']          Limit number of entities (default 0 = no limit)
 * @uses $vars['name']           Name of the returned data array (default "entities")
 * @uses $vars['handler']        Name of page handler used to power search (default "livesearch")
 * @uses $vars['options']        Additional options to pass to the handler with the URL query
 *                               If using custom options, make sure to impose a signed request gatekeeper in the resource view
 * @uses $vars['placeholder']    Optional placeholder text for the input
 * @uses $vars['item_view']      The item view to use for the display of the values (default 'input/autocomplete/item')
 * @uses $vars['sortable']       Boolean to control if items in the list are sortable
 *
 * Defaults to lazy load entity lists in alphabetical order. User needs to type two characters before seeing the user popup list.
 *
 * As entities are selected they move down to a "entities" box.
 * When this happens, a hidden input is created to return the GUID in the array with the form
 */

$name = elgg_extract('name', $vars, 'entities', false);

$guids = (array) elgg_extract('values', $vars, elgg_extract('value', $vars, []));
$limit = (int) elgg_extract('limit', $vars, 0);

$save_as_array = (bool) elgg_extract('save_as_array', $vars, true);
if ($limit !== 1) {
	$save_as_array = true;
}

$params = elgg_extract('options', $vars, []);

if (!empty($params)) {
	ksort($params);

	// We sign custom parameters, so that plugins can validate
	// that the request is unaltered, if needed
	$mac = elgg_build_hmac($params);
	$params['mac'] = $mac->getToken();
}

$params['view'] = 'json'; // force json viewtype
$params['save_as_array'] = $save_as_array;

$wrapper_options = [
	'class' => elgg_extract_class($vars, ['elgg-entity-picker']),
	'data-limit' => $limit,
	'data-name' => $name,
	'data-match-on' => elgg_extract('match_on', $vars, 'entities', false),
	'data-handler' => elgg_http_add_url_query_elements(elgg_extract('handler', $vars, 'livesearch'), $params),
];

$picker = elgg_format_element('input', [
	'type' => 'text',
	'class' => [
		'elgg-input-entity-picker',
	],
	'size' => 30,
	'id' => elgg_extract('id', $vars),
	'placeholder' => elgg_extract('placeholder', $vars),
]);

$picker .= elgg_view('input/hidden', ['name' => $name]);
$picker .= elgg_extract('picker_extras', $vars, '');

$item_view = elgg_extract('item_view', $vars, 'input/autocomplete/item');
$items = '';
foreach ($guids as $guid) {
	$entity = get_entity((int) $guid);
	if (!$entity instanceof \ElggEntity) {
		continue;
	}
	
	$items .= elgg_view($item_view, [
		'entity' => $entity,
		'input_name' => $name,
		'save_as_array' => $save_as_array,
	]);
}

$list_class = [
	'elgg-list',
	'elgg-entity-picker-list',
];
if (elgg_extract('sortable', $vars)) {
	$list_class[] = 'elgg-entity-picker-sortable';
}

$picker .= elgg_format_element('ul', ['class' => $list_class], $items);

echo elgg_format_element('div', $wrapper_options, $picker);

?>
<script>
	import('input/entitypicker').then((entitypicker) => {
		entitypicker.default.setup('.elgg-entity-picker[data-name=<?= json_encode($name) ?>]');
	});
</script>
