<?php

$default = array('Entry 1', 'Entry 2', 'Entry 3');

$menu = trigger_plugin_hook('get_menu_items', 'menu', null, $default);

foreach ($menu as $item) {
	var_dump($item);
}
