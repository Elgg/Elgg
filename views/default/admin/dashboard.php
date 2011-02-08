<?php

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

$params = array(
	'num_columns' => 2,
	'exact_match' => true,
	'show_access' => false,
);
$widgets = elgg_view_layout('widgets', $params);

echo $widgets;