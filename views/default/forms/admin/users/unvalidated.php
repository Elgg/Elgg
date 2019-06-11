<?php

echo elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() {
	return elgg_list_entities([
		'type' => 'user',
		'metadata_name_value_pairs' => [
			'validated' => 0,
		],
		'item_view' => 'admin/users/unvalidated/user',
		'list_class' => 'admin-users-unvalidated elgg-list-distinct',
	]);
});
