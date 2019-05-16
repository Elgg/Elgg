<?php
/**
 * Renders a list of groups with most members
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 */

echo elgg_list_entities_from_relationship_count([
	'type' => 'group',
	'relationship' => 'member',
	'inverse_relationship' => false,
	'full_view' => false,
	'pagination' => false,
]);
