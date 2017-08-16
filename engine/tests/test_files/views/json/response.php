<?php
elgg_set_http_header("Content-type: application/json; charset=UTF-8");

echo json_encode([
	'foo' => 'bar',
]);