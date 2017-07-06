<?php

$item = ElggMenuItem::factory([
	'name' => 'require',
	'href' => '#',
	'text' => 'Try Me!',
	'deps' => ['theme_sandbox/navigation/require'],
]);

$params = [];
$params['menu'] = [];
$params['menu']['default'] = [];
$params['menu']['default'][] = $item;
$params['name'] = 'require';
$params['class'] = 'elgg-menu-hz';

?>

<div class="theme-sandbox-demo-sidebar">
<?php
	echo elgg_view('navigation/menu/default', $params);
?>
</div>
