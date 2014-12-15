<?php

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:views') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:file_location') . "</th>";
echo "</tr>";

foreach ($data as $key => $arr) {
	echo "<tr>";
	echo "<td>$key</td>";
	echo "<td>{$arr[0]}</td>";
	echo "</tr>";
}

echo "</table>";
