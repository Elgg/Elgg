<?php

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:service:name') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:service:class') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:service:path') . "</th>";
echo "</tr>";

foreach ($data as $key => $arr) {
	echo "<tr>";
	echo "<td>$key</td>";
	echo "<td>{$arr[0]}</td>";
	echo "<td>{$arr[1]}</td>";
	echo "</tr>";
}

echo "</table>";
