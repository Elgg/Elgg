<?php
/**
 * WARNING! This view is internal and may change at any time.
 * Plugins should not use/modify/override this view.
 */

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

foreach ($data as $key => $arr) {

	$menu = "<table class='elgg-table-alt'>";
	
	foreach ($arr as $subkey => $value) {
		$menu .= "<tr>";
		
		$menu .= "<td>{$subkey}</td>";
		$menu .= "<td><ul>";
		foreach ($value as $item) {
			$menu .= "<li>$item</li>";
		}
		$menu .= "</ul></td>";
		$menu .= "</tr>";
	}

	$menu .= "</table>";
	
	echo elgg_view_module("inline", $key, $menu);
	
}
