<?php
/**
 * Renders a list of groups by creation date
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 */

echo elgg_list_entities([
	'type' => 'group',
	'full_view' => false,
	'pagination' => false,
]);
