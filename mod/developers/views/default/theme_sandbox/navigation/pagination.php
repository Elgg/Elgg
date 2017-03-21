<?php
$params = [
	'count' => 1000,
	'limit' => elgg_get_config('default_limit'),
	'offset' => 230,
];

echo elgg_view('navigation/pagination', $params);
