<?php

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo '<thead><tr>';
echo elgg_format_element('th', [], elgg_echo('developers:inspect:route'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:path'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:handler_type'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:middleware'));
echo '</tr></thead>';
echo '<tbody>';

foreach ($data as $key => $arr) {
	echo '<tr>';
	echo elgg_format_element('td', [], $key);
	echo elgg_format_element('td', [], $arr[0]);
	echo '<td>';
	if ($arr[1]) {
		$view_id = 'z' . md5("resources/{$arr[1]}");
		$info = elgg_view_url("admin/develop_tools/inspect?inspect_type=Views#{$view_id}", $arr[1]);
		$label = elgg_echo('developers:inspect:resource');
		echo "<b>{$label}</b>:<br /> {$info}";
	} else if ($arr[2]) {
		$label = elgg_echo('developers:inspect:handler');
		echo "<b>{$label}</b>:<br /> {$arr[2]}";
	} else if ($arr[3]) {
		$label = elgg_echo('developers:inspect:controller');
		echo "<b>{$label}</b>:<br /> {$arr[3]}";
	} else if ($arr[4]) {
		$label = elgg_echo('developers:inspect:file');
		echo "<b>{$label}</b>:<br /> {$arr[4]}";
	}
	
	echo '</td>';

	echo elgg_format_element('td', [], implode('<br />', $arr[5]));

	echo '</tr>';
}

echo '</tbody>';
echo '</table>';
