<?php

$services = list_all_apis();
if (empty($services)) {
	echo elgg_echo('notfound');
	return;
}

$lis = [];

foreach ($services as $service => $params) {
	$lis[] = elgg_view('webservices/service', [
		'service' => $service,
		'params' => $params,
	]);
}

echo elgg_format_element('ul', ['class' => 'elgg-list'], implode(PHP_EOL, $lis));
