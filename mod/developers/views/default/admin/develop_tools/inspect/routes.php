<?php

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:route') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:path') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:resource') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:handler') . "</th>";
echo "</tr>";

foreach ($data as $key => $arr) {
	echo "<tr>";
	echo "<td>$key</td>";
	echo "<td>{$arr[0]}</td>";
	echo "<td>";
	if ($arr[1]) {
		$view_id = "z" . md5("resources/{$arr[1]}");
		echo elgg_view('output/url', [
			'text' => $arr[1],
			'href' => "admin/develop_tools/inspect?inspect_type=Views#$view_id",
		]);
	}
	echo "</td>";
	echo "<td>{$arr[2]}</td>";
	echo "</tr>";
}

echo "</table>";
