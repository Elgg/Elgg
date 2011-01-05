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

$columns = array('type', 'name', 'value', 'local_value', 'comment');

echo '<table class="elgg-plugins-dependencies styled">
	<tr>
';

foreach ($columns as $column) {
	$column = elgg_echo("admin:plugins:dependencies:$column");
	echo "<th>$column</th>";
}

echo '<tr/>';

foreach ($deps as $dep) {
	$fields = elgg_get_plugin_dependency_strings($dep);

	if ($dep['status']) {
		$class = 'elgg-satisfied-dependency';
	} else {
		$class = 'elgg-unsatisfied-dependency';
	}

	echo "<tr class=\"$class\">";

	foreach ($columns as $column) {
		echo "<td class=\"pam \">{$fields[$column]}</td>";
	}

	echo '</tr>';
}

echo '</table>';