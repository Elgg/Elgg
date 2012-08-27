<?php
/**
 * Displays a tree for inspection
 *
 * @uses $vars['tree']
 */

echo "<ul>";
foreach ($vars['tree'] as $key => $arr) {
	echo "<li><a>$key</a>";
	echo "<ul>";
	foreach ($arr as $subkey => $value) {
		if(is_array($value)){
			// recursivity - we can have as much leaf depth as we need
			echo elgg_view('developers/tree', array('tree' => array($subkey => $value)));
		}
		else{
			echo "<li><a>$value</a></li>";
		}
	}
	echo "</ul>";
	echo "</li>";
}
echo "</ul>";
