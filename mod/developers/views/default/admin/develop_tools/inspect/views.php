<?php

$data = elgg_extract("data", $vars);

if (empty($data)) {
	return;
}

$root = elgg_get_root_path();
$strip = function ($file) use ($root) {
	return (0 === strpos($file, $root)) ? substr($file, strlen($root)) : $file;
};
$make_id = function ($view) {
	return "v_" . str_replace('/', '---', $view);
};

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:views') . "</th>";
echo "<th width='1%'>" . elgg_echo('developers:inspect:priority') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:file_location') . "</th>";
echo "</tr>";

$last_view = '';
foreach ($data as $view => $components) {
	/* @var \Elgg\Debug\Inspector\ViewComponent[] $components */

	$view_id = $make_id($view);

	foreach ($components as $priority => $component) {
		$file = $strip($component->getFile());

		echo "<tr>";
		if ($view !== $last_view) {
			echo "<td id='$view_id' rowspan='" . count($components) . "'>$view</td>";
			$last_view = $view;
		}

		if (0 === strpos($priority, "o:")) {
			echo "<td style='opacity:.6'>over</td>";
			echo "<td style='opacity:.6'><del>$file</del></td>";
		} elseif ($priority != 500) {
			$href = $make_id($component->view);
			echo "<td>$priority</td>";
			$link = elgg_view('admin/develop_tools/inspect/views/view_link', array(
				'view' => $component->view,
				'text' => $file,
			));
			echo "<td style='opacity:.6'>$link</td>";
		} else {
			echo "<td>$priority</td>";
			echo "<td>$file</td>";
		}
		echo "</tr>";
	}
}

echo "</table>";
