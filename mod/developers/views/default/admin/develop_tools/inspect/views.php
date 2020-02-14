<?php

$data = elgg_extract("data", $vars);
if (empty($data)) {
	return;
}

$views = elgg_extract('views', $data);
$global_hooks = elgg_extract('global_hooks', $data);
$filtered_views = elgg_extract('filtered_views', $data);
$input_filtered_views = (array) elgg_extract('input_filtered_views', $data);

$root = elgg_get_root_path();
$strip = function ($file) use ($root) {
	return (0 === strpos($file, $root)) ? substr($file, strlen($root)) : $file;
};
$make_id = function ($view) {
	return "z" . md5($view);
};

$viewtypes = elgg_extract("viewtypes", $vars);

foreach ($viewtypes as $type) {
	$href = "admin/develop_tools/inspect?inspect_type=Views";
	if ($type !== "default") {
		$href .= "&type={$type}";
	}
	elgg_register_menu_item('developers_inspect_viewtype', [
		'name' => $type,
		'text' => $type,
		'href' => $href,
	]);
}

echo elgg_view_menu('developers_inspect_viewtype', [
	'class' => 'elgg-tabs mbm',
]);

if ($global_hooks) {
	array_walk($global_hooks, function (&$hook) {
		$id = "z" . md5($hook);
		$hook = "<a href='?inspect_type=Plugin%20Hooks#$id'>$hook</a>";
	});

	echo "<p>" . elgg_echo("developers:inspect:views:all_filtered") . " ";
	echo implode(' | ', $global_hooks);
	echo "</p>";
}

echo "<table class='elgg-table-alt'>";
echo "<tr>";
echo "<th>" . elgg_echo('developers:inspect:views') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:priority') . "</th>";
echo "<th>" . elgg_echo('developers:inspect:file_location') . "</th>";
echo "</tr>";

$last_view = '';
foreach ($views as $view => $components) {
	/* @var \Elgg\Debug\Inspector\ViewComponent[] $components */

	$view_id = $make_id($view);

	$rowspan = count($components);

	$extra_rows = '';

	if (in_array($view, $input_filtered_views)) {
		$rowspan += 1;
		$id = "z" . md5("view_vars, $view");
		$link = "<a href='?inspect_type=Plugin%20Hooks#$id'>view_vars, $view</a>";
		$col2 = elgg_echo('developers:inspect:views:input_filtered', [$link]);

		$extra_rows .= "<tr><td>&nbsp;</td><td>$col2</td></tr>";
	}

	if (in_array($view, $filtered_views)) {
		$rowspan += 1;
		$id = "z" . md5("view, $view");
		$link = "<a href='?inspect_type=Plugin%20Hooks#$id'>view, $view</a>";
		$col2 = elgg_echo('developers:inspect:views:filtered', [$link]);

		$extra_rows .= "<tr><td>&nbsp;</td><td>$col2</td></tr>";
	}

	foreach ($components as $priority => $component) {
		$file = $strip($component->file);

		echo "<tr>";
		if ($view !== $last_view) {
			echo "<td id='$view_id' rowspan='$rowspan'>$view</td>";
			$last_view = $view;
		}

		if (0 === strpos($priority, "o:")) {
			echo "<td style='opacity:.6'>over</td>";
			echo "<td style='opacity:.6'><del>$file</del></td>";
		} elseif ($priority != 500) {
			$href = $make_id($component->view);
			echo "<td>$priority</td>";
			$link = elgg_view('admin/develop_tools/inspect/views/view_link', [
				'view' => $component->view,
				'text' => $file,
			]);
			echo "<td style='opacity:.6'>$link</td>";
		} else {
			echo "<td>$priority</td>";
			echo "<td>$file</td>";
		}
		echo "</tr>";
	}

	echo $extra_rows;
}

echo "</table>";
