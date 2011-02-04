<?php
/**
 * Shows a table of plugin dependecies for ElggPlugin in $vars['plugin'].
 *
 * This uses a table because it's a table of data.
 *
 * @package Elgg.Core
 * @subpackage Admin.Plugins
 */

$plugin = elgg_get_array_value('plugin', $vars, false);
$deps = $plugin->package->checkDependencies(true);

$columns = array('type', 'name', 'expected_value', 'local_value', 'comment');

echo '<table class="elgg-plugins-dependencies styled">
	<tr>
';

foreach ($columns as $column) {
	$column = elgg_echo("admin:plugins:dependencies:$column");
	echo "<th class=\"pas\">$column</th>";
}

echo '<tr/>';

$row = 'odd';
foreach ($deps as $dep) {
	$fields = elgg_get_plugin_dependency_strings($dep);

	if ($dep['status']) {
		$class = 'elgg-satisfied-dependency';
	} else {
		$class = 'elgg-unsatisfied-dependency';
	}

	echo "<tr class=\"$row\">";

	foreach ($columns as $column) {
		echo "<td class=\"pas $class\">{$fields[$column]}</td>";
	}

	echo '</tr>';

	$row = ($row == 'odd') ? 'even' : 'odd';
}

echo '</table>';