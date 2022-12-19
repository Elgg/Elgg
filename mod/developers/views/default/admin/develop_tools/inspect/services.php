<?php

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo '<thead><tr>';
echo elgg_format_element('th', [], elgg_echo('developers:inspect:service:name'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:service:class'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:service:path'));
echo '</tr></thead>';
echo '<tbody>';

foreach ($data as $key => $arr) {
	echo '<tr>';
	echo elgg_format_element('td', [], $key);
	echo elgg_format_element('td', [], $arr[0]);
	echo elgg_format_element('td', [], $arr[1]);
	echo '</tr>';
}

echo '</tbody>';
echo '</table>';
