<?php

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:webservices') . "</th>";
echo "<th>&nbsp;</th>";
echo "</tr>";

foreach ($data as $key => $arr) {
	echo "<tr>";
	echo "<td>$key</td>";
	echo "<td><ul>";
	foreach ($arr as $subkey => $value) {
		echo "<li>$value</li>";
	}
	echo "</ul></td>";
	echo "</tr>";
}

echo "</table>";
