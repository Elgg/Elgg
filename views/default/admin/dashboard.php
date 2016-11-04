<?php

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

echo elgg_view_layout('widgets', [
	'num_columns' => 2,
	'show_access' => false,
	'owner_guid' => elgg_get_logged_in_user_guid(),
]);
