<?php

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:widgets') . "</th>";
echo "<th>" . elgg_echo('title') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:widgets:context') . "</th>";
echo "</tr>";

foreach ($data as $name => $arr) {
	$view = "widgets/$name/content";
	$link = elgg_view('admin/develop_tools/inspect/views/view_link', [
		'view' => $view,
		'text' => $name,
	]);

	echo "<tr>";
	echo "<td>$link</td>";
	echo "<td>{$arr[0]}</td>";
	echo "<td>{$arr[1]}</td>";
	echo "</tr>";
}

echo "</table>";
