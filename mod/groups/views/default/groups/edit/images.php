<?php
/**
 * Group edit form
 *
 * This view contains the group images configuration
 */

echo elgg_view('entity/edit/icon', [
	'entity' => elgg_extract('entity', $vars),
	'entity_type' => 'group',
	'entity_subtype' => 'group',
]);

echo elgg_view('entity/edit/header', [
	'entity' => elgg_extract('entity', $vars),
	'entity_type' => 'group',
	'entity_subtype' => 'group',
]);
