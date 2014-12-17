<?php

$data = elgg_extract("data", $vars);
$header = elgg_extract("header", $vars);

if (!$header) {
	$header = elgg_echo('developers:inspect:events');
}

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>$header</th>";
echo "<th width='1%'>" . elgg_echo('developers:inspect:priority') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:functions') . "</th>";
echo "</tr>";

$last_key = '';
foreach ($data as $key => $arr) {
	foreach ($arr as $subkey => $value) {
		list($priority, $desc) = explode(': ', $value, 2);
		echo "<tr>";
		if ($key !== $last_key) {
			echo "<td rowspan='" . count($arr) . "'>$key</td>";
			$last_key = $key;
		}
		echo "<td>$priority</td>";
		echo "<td>$desc</td>";
		echo "</tr>";
	}
}

echo "</table>";
