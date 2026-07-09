<?php
/**
 * Page edit form body
 */

$fields = elgg_extract('fields', $vars);
if (empty($fields)) {
	return;
}

$entity = elgg_extract('entity', $vars);
$parent_guid = elgg_extract('parent_guid', $vars);

$can_change_access = true;
if ($entity instanceof \ElggPage && $entity->getOwnerEntity()) {
	$can_change_access = $entity->getOwnerEntity()->canEdit();
}

foreach ($fields as $key => $field) {
	switch (elgg_extract('name', $field)) {
		case 'access_id':
		case 'write_access_id':
			if (!$can_change_access) {
				// Only owner and admins can change access
				unset($fields[$key]);
				continue(2);
			}
			break;

		case 'parent_guid':
			if (empty($parent_guid)) {
				// skip field if there is no parent_guid
				unset($fields[$key]);
				continue(2);
			}
			
			$fields[$key]['entity'] = $entity;
			break;
	}
}

$vars['fields'] = $fields;
$vars['add_header_image'] = true;

echo elgg_view('forms/entity/edit', $vars);
