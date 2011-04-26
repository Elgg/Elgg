<?php
$params = array(
	'tabs' => array(
		array('title' => 'First', 'url' => "$url#"),
		array('title' => 'Second', 'url' => "$url#", 'selected' => true),
		array('title' => 'Third', 'url' => "$url#"),
	)
);

echo elgg_view('navigation/tabs', $params);