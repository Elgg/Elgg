<?php
$params = array();
$params['menu'] = array();
$params['menu']['default'] = array();
$params['menu']['default'][] = new ElggMenuItem(1, "Public", false);
$params['menu']['default'][] = new ElggMenuItem(2, "Edit", "#");
$params['menu']['default'][] = new ElggMenuItem(3, elgg_view_icon('thumbs-up'), "#");
$params['name'] = 'entity';
$params['class'] = 'elgg-menu-hz';

echo elgg_view('navigation/menu/default', $params);

