<?php
$params = array(
	'count' => 1000,
	'limit' => 10,
	'offset' => 230,
);

echo elgg_view('navigation/pagination', $params);