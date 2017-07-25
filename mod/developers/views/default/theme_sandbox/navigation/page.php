<?php

$params = [];
$params['name'] = 'sandbox_page';
$params['menu'] = [];
$params['menu']['default'] = [];
for ($i = 1; $i <= 5; $i++) {
	$params['menu']['default'][] = ElggMenuItem::factory([
			'name' => $i,
			'text' => "Page $i",
			'href' => "#",
			'icon' => 'file-text',
	]);
}

$second_item = $params['menu']['default'][1];
/* @var ElggMenuItem $second_item */

$third_item = $params['menu']['default'][2];
/* @var ElggMenuItem $third_item */

$third_item->setSelected(true);

$m = new ElggMenuItem(10, "Child", "#");
$m->setParent($second_item);

$second_item->addChild($m);
$second_item->setChildMenuOptions([
	'display' => 'toggle',
]);
?>

<div class="theme-sandbox-demo-sidebar">
<?php
	echo elgg_view('navigation/menu/page', $params);
?>
</div>
