<?php
/**
 * WARNING! This view is internal and may change at any time.
 * Plugins should not use/modify/override this view.
 */

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:actions') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:file_location') . "</th>";
echo "<th>" . elgg_echo('access') . "</th>";
echo "</tr>";

foreach ($data as $key => $arr) {
	echo "<tr>";
	echo "<td>$key</td>";
	echo "<td>{$arr[0]}</td>";
	echo "<td>{$arr[1]}</td>";
	echo "</tr>";
}

echo "</table>";
