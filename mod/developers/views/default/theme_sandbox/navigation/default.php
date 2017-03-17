<?php

$params = [];
$params['menu'] = [];
$params['menu']['default'] = [];
for ($i=1; $i<=5; $i++) {
	$params['menu']['default'][] = new ElggMenuItem($i, "Page $i", "#");
}
$params['menu']['default'][2]->setSelected(true);

echo elgg_view('navigation/menu/default', $params);
