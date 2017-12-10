<?php
/**
 * Shows a table of plugin dependecies for ElggPlugin in $vars['plugin'].
 *
 * This uses a table because it's a table of data.
 *
 * @package Elgg.Core
 * @subpackage Admin.Plugins
 */

$plugin = elgg_extract('plugin', $vars, false);
$deps = $plugin->getPackage()->checkDependencies(true);

$columns = ['type', 'name', 'expected_value', 'local_value', 'comment'];

echo '<table class="elgg-plugin-dependencies styled elgg-table"><tr>';

foreach ($columns as $column) {
	$column = elgg_echo("admin:plugins:dependencies:$column");
	echo "<th class=\"pas\">$column</th>";
}

echo '</tr>';

foreach ($deps as $dep) {
	$fields = _elgg_services()->plugins->getDependencyStrings($dep);
	$type = $dep['type'];

	if ($dep['status']) {
		$class = "elgg-state-success elgg-dependency elgg-dependency-$type";
	} elseif ($dep['type'] == 'suggests') {
		$class = "elgg-state-warning elgg-dependency elgg-dependency-$type";
	} else {
		$class = "elgg-state-error elgg-dependency elgg-dependency-$type";
	}

	echo "<tr>";

	foreach ($columns as $column) {
		echo "<td class=\"pas $class\">{$fields[$column]}</td>";
	}

	echo '</tr>';
}

echo '</table>';
