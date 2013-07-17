<?php
$params = array();
$params['menu'] = array();
$params['menu']['default'] = array();
for ($i=1; $i<=5; $i++) {
	$params['menu']['default'][] = new ElggMenuItem($i, "Page $i", "#");
}
$params['menu']['default'][2]->setSelected(true);
$params['name'] = 'owner-block';

echo '<div class="elgg-sidebar">';
echo elgg_view('navigation/menu/default', $params);
echo '</div>';
