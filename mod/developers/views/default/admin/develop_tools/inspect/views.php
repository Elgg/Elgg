<?php

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

$views = elgg_extract('views', $data);
$global_events = elgg_extract('global_events', $data);
$filtered_views = (array) elgg_extract('filtered_views', $data);
$input_filtered_views = (array) elgg_extract('input_filtered_views', $data);

$root = elgg_get_root_path();
$strip = function ($file) use ($root) {
	return str_starts_with($file, $root) ? substr($file, strlen($root)) : $file;
};

$make_id = function ($view) {
	return 'z' . md5($view);
};

$viewtypes = elgg_extract('viewtypes', $vars);

foreach ($viewtypes as $type) {
	elgg_register_menu_item('developers_inspect_viewtype', [
		'name' => $type,
		'text' => $type,
		'href' => elgg_http_add_url_query_elements('admin/develop_tools/inspect', [
			'inspect_type' => 'Views',
			'type' => $type !== 'default' ? $type : null,
		]),
	]);
}

echo elgg_view_menu('developers_inspect_viewtype', [
	'class' => 'elgg-tabs mbm',
]);

if ($global_events) {
	array_walk($global_events, function (&$event) {
		$id = 'z' . md5($event);
		$event = "<a href='?inspect_type=Events#{$id}'>{$event}</a>";
	});

	echo elgg_format_element('p', [], elgg_echo('developers:inspect:views:all_filtered') . ' ' . implode(' | ', $global_events));
}

echo "<table class='elgg-table-alt'>";
echo '<thead><tr>';
echo elgg_format_element('th', [], elgg_echo('developers:inspect:views'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:priority'));
echo elgg_format_element('th', [], elgg_echo('developers:inspect:file_location'));
echo '</tr></thead>';
echo '<tbody>';

$last_view = '';
/* @var \Elgg\Debug\Inspector\ViewComponent[] $components */
foreach ($views as $view => $components) {
	$view_id = $make_id($view);

	$rowspan = count($components);

	$extra_rows = '';

	if (in_array($view, $input_filtered_views)) {
		$rowspan += 1;
		$id = 'z' . md5("view_vars, {$view}");
		$link = "<a href='?inspect_type=Events#{$id}'>view_vars, {$view}</a>";
		$col2 = elgg_echo('developers:inspect:views:input_filtered', [$link]);

		$extra_rows .= "<tr><td>&nbsp;</td><td>$col2</td></tr>";
	}

	if (in_array($view, $filtered_views)) {
		$rowspan += 1;
		$id = 'z' . md5("view, {$view}");
		$link = "<a href='?inspect_type=Events#{$id}'>view, {$view}</a>";
		$col2 = elgg_echo('developers:inspect:views:filtered', [$link]);

		$extra_rows .= "<tr><td>&nbsp;</td><td>{$col2}</td></tr>";
	}

	foreach ($components as $priority => $component) {
		$file = $strip($component->file);

		echo '<tr>';
		if ($view !== $last_view) {
			echo elgg_format_element('td', ['id' => $view_id, 'rowspan' => $rowspan], $view);
			$last_view = $view;
		}

		if (str_starts_with($priority, 'o:')) {
			echo elgg_format_element('td', ['style' => 'opacity: 0.6;'], 'over');
			echo elgg_format_element('td', ['style' => 'opacity: 0.6;'], elgg_format_element('del', [], $file));
		} elseif ($priority != 500) {
			echo elgg_format_element('td', [], $priority);
			$link = elgg_view('admin/develop_tools/inspect/views/view_link', [
				'view' => $component->view,
				'text' => $file,
				'view_id' => $make_id($component->view),
			]);
			echo elgg_format_element('td', ['style' => 'opacity: 0.6;'], $link);
		} else {
			echo elgg_format_element('td', [], $priority);
			echo elgg_format_element('td', [], $file);
		}
		
		echo '</tr>';
	}

	echo $extra_rows;
}

echo '</tbody>';
echo '</table>';
