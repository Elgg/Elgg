<?php
$params = [
	'tabs' => [
		['text' => 'First', 'href' => "#"],
		['text' => 'Second', 'href' => "#", 'selected' => true],
		['text' => 'Third', 'href' => "#", 'icon' => 'question'],
	]
];

echo elgg_view('navigation/tabs', $params);
