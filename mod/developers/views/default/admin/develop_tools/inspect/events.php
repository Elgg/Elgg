<?php

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

$header = elgg_extract('header', $vars);
if (empty($header)) {
	$header = elgg_echo('developers:inspect:events');
}

$make_id = function ($name) {
	return 'z' . md5($name);
};

echo "<table class='elgg-table-alt'>";
echo '<thead><tr>';
echo elgg_format_element('th', [], $header);
echo elgg_format_element('th', [], elgg_echo('developers:inspect:priority'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:functions'));
echo '</tr></thead>';
echo '<tbody>';

$last_key = '';
foreach ($data as $key => $arr) {
	foreach ($arr as $value) {
		list($priority, $desc) = explode(': ', $value, 2);
		echo '<tr>';
		if ($key !== $last_key) {
			$id = $make_id($key);
			echo elgg_format_element('td', ['id' => $id, 'rowspan' => count($arr)], $key);
			$last_key = $key;
		}
		
		echo elgg_format_element('td', [], $priority);
		echo elgg_format_element('td', [], $desc);
		echo '</tr>';
	}
}

echo '</tbody>';
echo '</table>';
