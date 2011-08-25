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
	foreach ($arr as $value) {
		echo "<li><a>$value</a></li>";
	}
	echo "</ul>";
	echo "</li>";
}
echo "</ul>";
