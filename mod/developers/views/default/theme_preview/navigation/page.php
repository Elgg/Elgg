<?php

$params = array();
$params['menu'] = array();
$params['menu']['default'] = array();
for ($i=1; $i<=5; $i++) {
	$params['menu']['default'][] = new ElggMenuItem($i, "Page $i", "#");
	$params['menu']['alt'][] = new ElggMenuItem($i, "Page $i", "#");
}
$params['menu']['default'][2]->setSelected(true);

$m = new ElggMenuItem(10, "Child", "$url#");
$m->setParent($params['menu']['default'][1]);
$params['menu']['default'][1]->addChild($m);
?>

<div class="elgg-sidebar">
<?php 
	echo elgg_view('navigation/menu/page', $params);
?>
</div>