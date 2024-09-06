<?php
/**
 * View a table of items
 *
 * @uses $vars['columns']      Array of Elgg\Views\TableColumn
 *                             If the trimmed rendering doesn't start with "<td" or "<th", then the cell
 *                             is auto-wrapped with a TD/TH element.
 *
 * @uses $vars['items']        Array of ElggEntity, ElggAnnotation or ElggRiverItem objects
 * @uses $vars['offset']       Index of the first list item in complete list
 * @uses $vars['limit']        Number of items per page. Only used as input to pagination.
 * @uses $vars['count']        Number of items in the complete list
 * @uses $vars['base_url']     Base URL of list (optional)
 * @uses $vars['url_fragment'] URL fragment to add to links if not present in base_url (optional)
 * @uses $vars['pagination']   Show pagination? (default: true)
 * @uses $vars['position']     Position of the pagination: before, after, or both
 * @uses $vars['full_view']    Show the full view of the items (default: false)
 * @uses $vars['list_class']   Additional CSS class for the <table> element
 * @uses $vars['item_class']   Additional CSS class for the <td> elements
 * @uses $vars['item_view']    Alternative view to render list items
 */

use Elgg\Views\TableColumn;

$items = elgg_extract('items', $vars);
$pagination = elgg_extract('pagination', $vars, true);
$position = elgg_extract('position', $vars, 'after');

/* @var TableColumn[] $columns */
$columns = elgg_extract('columns', $vars);
if (empty($columns) || !is_array($columns)) {
	return;
}

if (!is_array($items) || count($items) == 0) {
	echo elgg_view('page/components/no_results', $vars);
	return;
}

// render THEAD
$headings = '';
foreach ($columns as $column) {
	if (!$column instanceof TableColumn) {
		elgg_log('$vars["columns"] must be an array of ' . TableColumn::class, \Psr\Log\LogLevel::NOTICE);
		return;
	}

	$cell = trim($column->renderHeading());
	if (!preg_match('~^<t[dh]~i', $cell)) {
		$cell = elgg_format_element('th', [], $cell);
	}
	
	$headings .= $cell;
}

$headings = "<thead><tr>$headings</tr></thead>";

$table_classes = elgg_extract_class($vars, ['elgg-list', 'elgg-table'], 'list_class');

$nav = ($pagination) ? elgg_view('navigation/pagination', $vars) : '';

$rows = '';
foreach ($items as $item) {
	$row_attrs = [
		'class' => elgg_extract_class($vars, 'elgg-item', 'item_class'),
	];

	$type = '';
	if ($item instanceof \ElggEntity) {
		$guid = $item->guid;
		$type = $item->type;
		$subtype = $item->getSubtype();

		$row_attrs['id'] = "elgg-$type-$guid";
		$row_attrs['class'][] = "elgg-item-$type";
		$row_attrs['data-elgg-guid'] = $guid;
		$row_attrs['data-elgg-type-subtype'] = "$type:$subtype";
		if ($subtype) {
			$row_attrs['class'][] = "elgg-item-$type-$subtype";
		}
	} elseif (is_callable([$item, 'getType'])) {
		$type = $item->getType();

		$row_attrs['id'] = "elgg-$type-{$item->id}";
		$row_attrs['data-elgg-id'] = $item->id;
		$row_attrs['data-elgg-type'] = $type;
	}

	$row = '';

	foreach ($columns as $column) {
		$cell = trim($column->renderCell($item, $type, $vars));
		if (!preg_match('~^<t[dh]~i', $cell)) {
			$cell = elgg_format_element('td', [], $cell);
		}
		
		$row .= $cell;
	}

	$rows .= elgg_format_element('tr', $row_attrs, $row);
}

$body = "$headings<tbody>$rows</tbody>";

if ($position == 'before' || $position == 'both') {
	echo $nav;
}

echo elgg_format_element('table', ['class' => $table_classes], $body);

if ($position == 'after' || $position == 'both') {
	echo $nav;
}

$limit = elgg_extract('limit', $vars);
if (!$pagination && $limit !== false && !empty($items) && count($items) >= $limit) {
	echo elgg_view('page/components/list/widget_more', $vars);
}
