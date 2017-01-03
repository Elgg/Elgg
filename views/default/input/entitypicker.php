<?php
/**
 * Entity Picker.  Sends an array of user guids.
 *
 * @uses $vars['limit'] Limit number of users (default 0 = no limit)
 * @uses $vars['name'] Name of the returned data array (default "members")
 * @uses $vars['handler'] Name of page handler used to power search (default "livesearch")
 * @uses $vars['guids'] Array of entity guids for already selected entities or null
 * @uses $vars['filter'] (optional) additional filters that will be used performing a search
 *
 * As entities are selected they move down to a "elgg-entity-picker-list" box.
 * When this happens, a hidden input is created to return the GUID in the array with the form
 * This behaviour can be altered within the html views of the entity picker results
 */

$name = elgg_extract('name', $vars);
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
unset($vars['name']);

$class = elgg_extract_class($vars, 'elgg-input-entity-picker');
unset($vars['class']);

$limit = (int) elgg_extract('limit', $vars, 0);
unset($vars['limit']);

$output = elgg_extract('filter', $vars);

$items_list = '';
$guids = (array) elgg_extract('guids', $vars, []);
unset($vars['guids']);

foreach ($guids as $guid) {
	$entity = get_entity($guid);
	if (!$entity) {
		continue;
	}
	
	$items_list .= elgg_view(elgg_livesearch_get_view($entity), [
		'entity' => $entity,
		'input_name' => $name,
	]);
}

$output .= elgg_format_element('ul', ['class' => 'elgg-entity-picker-list'], $items_list);

unset($vars['filter'], $vars['items']);

$vars['size'] = 30;
$input = elgg_view('input/autocomplete', $vars);

echo elgg_format_element([
	'#tag_name' => 'div',
	'#text' => $input . $output,
	'class' => $class,
	'data-limit' => $limit,
	'data-name' => $name,
]);

?>
<script>
require(['input/entitypicker'], function (EntityPicker) {
	EntityPicker.init('.elgg-input-entity-picker[data-name="<?php echo $name ?>"] ');
});
</script>
