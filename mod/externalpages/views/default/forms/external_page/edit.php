<?php
/**
 * Edit form body for external pages
 */

$page = elgg_extract('page', $vars);
$entity = elgg_extract('entity', $vars);

echo elgg_view('entity/edit/header', [
	'entity' => $entity,
	'entity_type' => 'object',
	'entity_subtype' => \ElggExternalPage::SUBTYPE,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'forward_url',
	'value' => elgg_get_current_url(),
]);

$fields = elgg()->fields->get('object', \ElggExternalPage::SUBTYPE);
foreach ($fields as $field) {
	$name = elgg_extract('name', $field);

	$field['value'] = elgg_extract($name, $vars);
	echo elgg_view_field($field);
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity?->guid,
]);

$footer = elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'submit',
			'text' => elgg_echo('save'),
		],
		[
			'#html' => elgg_view('output/url', [
				'icon' => 'external-link-alt',
				'text' => elgg_echo('external_pages:edit:viewpage'),
				'href' => elgg_generate_url("view:object:external_page:{$page}"),
				'target' => '_blank',
				'class' => ['elgg-button', 'elgg-button-action'],
			]),
		],
	],
]);

elgg_set_form_footer($footer);
