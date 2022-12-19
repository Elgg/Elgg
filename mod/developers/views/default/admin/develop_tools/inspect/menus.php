<?php

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

foreach ($data as $key => $arr) {
	if (empty($arr)) {
		continue;
	}
	
	$menu = "<table class='elgg-table-alt'>";
	foreach ($arr as $subkey => $value) {
		$menu .= '<tr>';
		
		$menu .= elgg_format_element('td', [], $subkey);
		$menu .= '<td><ul>';
		foreach ($value as $item) {
			$menu .= elgg_format_element('li', [], $item);
		}
		
		$menu .= '</ul></td>';
		$menu .= '</tr>';
	}

	$menu .= '</table>';
	
	echo elgg_view_module('inline', $key, $menu);
}
