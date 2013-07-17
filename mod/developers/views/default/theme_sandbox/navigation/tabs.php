<?php
$params = array(
	'tabs' => array(
		array('title' => 'First', 'url' => "#"),
		array('title' => 'Second', 'url' => "#", 'selected' => true),
		array('title' => 'Third', 'url' => "#"),
	)
);

echo elgg_view('navigation/tabs', $params);