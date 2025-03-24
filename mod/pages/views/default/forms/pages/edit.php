<?php
/**
 * Page edit form body
 */

$fields = elgg()->fields->get('object', 'page');
if (empty($fields)) {
	return;
}

$entity = elgg_extract('entity', $vars);
$parent_guid = elgg_extract('parent_guid', $vars);

$can_change_access = true;
if ($entity instanceof ElggPage && $entity->getOwnerEntity()) {
	$can_change_access = $entity->getOwnerEntity()->canEdit();
}

echo elgg_view('entity/edit/header', [
	'entity' => $entity,
	'entity_type' => 'object',
	'entity_subtype' => 'page',
]);

foreach ($fields as $field) {
	$name = $field['name'];
	
	switch ($name) {
		case 'access_id':
		case 'write_access_id':
			if (!$can_change_access) {
				// Only owner and admins can change access
				continue(2);
			}

			$field['entity'] = $entity;
			break;

		case 'parent_guid':
			if (empty($parent_guid)) {
				// skip field if there is no parent_guid
				continue(2);
			}
			
			$field['entity'] = $entity;
			break;
	}

	$field['value'] = elgg_extract($name, $vars);
	
	echo elgg_view_field($field);
}

if ($entity instanceof \ElggPage) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'page_guid', // @todo rename to 'guid' in Elgg 7.0
		'value' => $entity->guid,
	]);
}

echo elgg_view_field([
	'#type' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
	'entity_type' => 'object',
	'entity_subtype' => 'page',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
