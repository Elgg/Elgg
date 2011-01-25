<?php

elgg_set_page_owner_guid(get_loggedin_userid());

$params = array(
	'num_columns' => 2,
	'exact_match' => true,
	'show_access' => false,
);
$widgets = elgg_view_layout('widgets', $params);

echo $widgets;