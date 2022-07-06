<?php

$data = elgg_extract('data', $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<thead><tr>";
echo "<th>" . elgg_echo('developers:inspect:simplecache') . "</th>";
echo "</tr></thead>";

foreach ($data as $view => $arr) {
	echo "<tr><td>";
	echo elgg_view('admin/develop_tools/inspect/views/view_link', [
		'view' => $view,
	]);
	echo "</td></tr>";
}

echo "</table>";
