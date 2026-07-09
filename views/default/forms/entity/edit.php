<?php
/**
 * Generic entity edit/add form
 *
 * @uses $vars['entity_type']      (required) the type of the entity
 * @uses $vars['entity_subtype']   (required) the subtype of the entity
 * @uses $vars['fields']           (required) the form fields to draw
 * @uses $vars['entity']           (optional) the entity being edited
 * @uses $vars['footer']           (optional) the form footer (false for no footer, is not provided get a default footer)
 * @uses $vars['header']           (optional) header text shown at the top of the form
 * @uses $vars['add_header_image'] (optional) (bool) add an input for an entity header image upload (default: false)
 */

$entity_type = elgg_extract('entity_type', $vars);
$entity_subtype = elgg_extract('entity_subtype', $vars);
$entity = elgg_extract('entity', $vars);
$fields = elgg_extract('fields', $vars);

if (empty($entity_type) || empty($entity_subtype) || empty($fields)) {
	return;
}

$header = elgg_extract('header', $vars);
if (is_string($header)) {
	echo $header;
}

if (elgg_extract('add_header_image', $vars)) {
	echo elgg_view('entity/edit/header', $vars);
}

foreach ($fields as $field) {
	$name = elgg_extract('name', $field);
	
	switch (elgg_extract('#type', $field)) {
		case 'checkbox':
			$field['checked'] = elgg_extract('value', $field) === elgg_extract($name, $vars);
			break;
		default:
			$field['value'] = elgg_extract($name, $vars, elgg_extract('value', $field));
			break;
	}
	
	echo elgg_view_field($field);
}

$footer = elgg_extract('footer', $vars);
if (!isset($footer)) {
	$footer = elgg_view_field([
		'#type' => 'submit',
		'text' => elgg_echo('save'),
	]);
}

if (!empty($footer)) {
	elgg_set_form_footer($footer);
}
