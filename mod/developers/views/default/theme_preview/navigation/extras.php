<?php

$params = array();
$params['menu'] = array();
$params['menu']['default'] = array();
$params['menu']['default'][] = new ElggMenuItem(1, elgg_view_icon('push-pin-alt'), "#");
$params['menu']['default'][] = new ElggMenuItem(2, elgg_view_icon('rss'), "#");
$params['menu']['default'][] = new ElggMenuItem(3, elgg_view_icon('star-alt'), "#");
$params['name'] = 'extras';
$params['class'] = 'elgg-menu-hz';

?>

<div class="elgg-sidebar">
<?php 
	echo elgg_view('navigation/menu/default', $params);
?>
</div>