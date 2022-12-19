<?php

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo '<thead><tr>';
echo elgg_format_element('th', [], elgg_echo('developers:inspect:widgets'));
echo elgg_format_element('th', [], elgg_echo('title'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:widgets:context'));
echo '</tr></thead>';
echo '<tbody>';

foreach ($data as $name => $arr) {
	$link = elgg_view('admin/develop_tools/inspect/views/view_link', [
		'view' => "widgets/{$name}/content",
		'text' => $name,
	]);

	echo '<tr>';
	echo elgg_format_element('td', [], $link);
	echo elgg_format_element('td', [], $arr[0]);
	echo elgg_format_element('td', [], $arr[1]);
	echo '</tr>';
}

echo '</tbody>';
echo '</table>';
