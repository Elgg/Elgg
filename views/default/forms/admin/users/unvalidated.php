<?php

$hidden = access_show_hidden_entities(true);

echo elgg_list_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => [
		'validated' => 0,
	],
	'item_view' => 'admin/users/unvalidated/user',
	'list_class' => 'admin-users-unvalidated elgg-list-distinct',
]);

access_show_hidden_entities($hidden);
