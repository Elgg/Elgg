<?php
/**
 * OPCache info
 */

$opcache_available = function_exists('opcache_get_status');
$opcache_status = false;

if ($opcache_available) {
	$opcache_status = opcache_get_status(false);
}

if (!$opcache_available || empty($opcache_status)) {
	echo '<p>' . elgg_echo('admin:server:opcache:inactive') . '</p>';
	return;
}

$array_to_table = function($array) use (&$array_to_table) {
	$rows = '';
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$rows .= "<tr><th colspan='2'><b>{$key}</b></th></tr>";
			
			$rows .= $array_to_table($value);
			continue;
		}
		
		if ($value === true) {
			$value = 'true';
		} elseif ($value === false) {
			$value = 'false';
		}
		
		$rows .= "<tr><td>{$key}</td><td>{$value}</td></tr>";
	}
	
	return $rows;
};

echo elgg_format_element('table', ['class' => 'elgg-table'], $array_to_table($opcache_status));
