<?php
$params = array();
$params['menu'] = array();
$params['menu']['default'] = array();
for ($i=1; $i<=5; $i++) {
	$params['menu']['default'][] = new ElggMenuItem($i, "Page $i", "#");
}
$params['menu']['alt'] = array();
for ($i=1; $i<=3; $i++) {
	$params['menu']['alt'][] = new ElggMenuItem($i, "Info $i", "#");
}
$params['name'] = 'footer';

echo elgg_view('navigation/menu/default', $params);
