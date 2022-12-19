<?php

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo '<thead><tr>';
echo elgg_format_element('th', [], elgg_echo('developers:inspect:simplecache'));
echo '</tr></thead>';
echo '<tbody>';

foreach ($data as $view => $arr) {
	echo '<tr><td>';
	echo elgg_view('admin/develop_tools/inspect/views/view_link', [
		'view' => $view,
	]);
	echo '</td></tr>';
}

echo '</tbody>';
echo '</table>';
