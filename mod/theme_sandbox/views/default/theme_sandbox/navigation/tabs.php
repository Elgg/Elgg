<?php
$params = [
	'tabs' => [
		['text' => 'First', 'href' => false],
		['text' => 'Second', 'href' => false, 'selected' => true],
		['text' => 'Third', 'href' => false, 'icon' => 'question'],
	]
];

echo elgg_view('navigation/tabs', $params);
