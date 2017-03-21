<?php
$params = [
	'tabs' => [
		['title' => 'First', 'url' => "#"],
		['title' => 'Second', 'url' => "#", 'selected' => true],
		['title' => 'Third', 'url' => "#", 'icon' => 'question'],
	]
];

echo elgg_view('navigation/tabs', $params);
