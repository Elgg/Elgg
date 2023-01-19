<?php
/**
 * Edit form body for external pages
 *
 * @uses $vars['type']
 */

$type = elgg_extract('type', $vars);
if (empty($type)) {
	return;
}

//grab the required entity
$page_contents = elgg_get_entities([
	'type' => 'object',
	'subtype' => $type,
	'limit' => 1,
]);

$description = '';
$guid = 0;
if (!empty($page_contents)) {
	$description = $page_contents[0]->description;
	$guid = $page_contents[0]->guid;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'content_type',
	'value' => $type,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $guid,
]);

echo elgg_view('entity/edit/header', [
	'entity' => elgg_extract(0, $page_contents),
	'entity_type' => 'object',
	'entity_subtype' => $type,
]);

// set the required form variables
echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo("expages:{$type}"),
	'name' => 'expagescontent',
	'value' => $description,
]);

$footer = elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'submit',
			'value' => elgg_echo('save'),
		],
		[
			'#html' => elgg_view('output/url', [
				'text' => elgg_echo('expages:edit:viewpage'),
				'href' => $type,
				'target' => '_blank',
				'class' => 'elgg-button elgg-button-action',
			]),
		],
	],
]);

elgg_set_form_footer($footer);
