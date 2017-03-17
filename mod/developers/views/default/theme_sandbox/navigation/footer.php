<?php
$params = [];
$params['menu'] = [];
$params['menu']['default'] = [];
for ($i=1; $i<=5; $i++) {
	$params['menu']['default'][] = new ElggMenuItem($i, "Page $i", "#");
}
$params['menu']['alt'] = [];
for ($i=1; $i<=3; $i++) {
	$params['menu']['alt'][] = new ElggMenuItem($i, "Info $i", "#");
}
$params['name'] = 'footer';

echo elgg_view('navigation/menu/default', $params);
