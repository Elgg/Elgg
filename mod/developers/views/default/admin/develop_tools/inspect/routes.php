<?php

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:route') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:path') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:handler_type') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:middleware') . "</th>";
echo "</tr>";

foreach ($data as $key => $arr) {
	echo "<tr>";
	echo "<td>$key</td>";
	echo "<td>{$arr[0]}</td>";
	echo "<td>";
	if ($arr[1]) {
		$view_id = "z" . md5("resources/{$arr[1]}");
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
	echo "</td>";

	echo '<td>';
	echo implode('<br />', $arr[5]);
	echo '</td>';

	echo "</tr>";
}

echo "</table>";
