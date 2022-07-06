<?php

$data = elgg_extract('data', $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<thead><tr>";
echo "<th>" . elgg_echo('developers:inspect:widgets') . "</th>";
echo "<th>" . elgg_echo('title') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:widgets:context') . "</th>";
echo "</tr></thead>";

foreach ($data as $name => $arr) {
	$link = elgg_view('admin/develop_tools/inspect/views/view_link', [
		'view' => "widgets/{$name}/content",
		'text' => $name,
	]);

	echo "<tr>";
	echo "<td>{$link}</td>";
	echo "<td>{$arr[0]}</td>";
	echo "<td>{$arr[1]}</td>";
	echo "</tr>";
}

echo "</table>";
