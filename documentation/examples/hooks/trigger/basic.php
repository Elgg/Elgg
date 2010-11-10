<?php

$result = elgg_trigger_plugin_hook('get_status', 'example', null, true);

if ($result) {
	var_dump('Plugin hook says ok!');
} else {
	var_dump('Plugin hook says no.');
}
