<?php
/**
 * Elgg file upload/save form
 */

$entity = elgg_extract('entity', $vars);

$fields = elgg()->fields->get('object', 'file');
foreach ($fields as $field) {
	$name = elgg_extract('name', $field);
	switch (elgg_extract('#type', $field)) {
		case 'file':
			if ($entity instanceof \ElggFile) {
				$field['value'] = $entity->getFilename();
				$field['#label'] = elgg_echo('file:replace');
			} else {
				// new uploads require a file
				$field['required'] = true;
			}
			break;
		case 'access':
			if ($entity instanceof \ElggFile) {
				$field['entity'] = $entity;
			}
			
			// fall through to default
		default:
			$field['value'] = elgg_extract($name, $vars);
			break;
	}
	
	echo elgg_view_field($field);
}

if ($entity instanceof \ElggFile) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'file_guid', // @todo rename to guid in Elgg 7.0
		'value' => $entity->guid,
	]);
}

echo elgg_view_field([
	'#type' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
	'entity_type' => 'object',
	'entity_subtype' => 'file',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => $entity instanceof \ElggFile ? elgg_echo('save') : elgg_echo('upload'),
]);
elgg_set_form_footer($footer);
