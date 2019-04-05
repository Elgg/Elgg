<?php
/**
 * Show a list of completed upgrades
 */

echo elgg_view('navigation/filter', [
	'filter_id' => 'admin/upgrades',
	'filter_value' => 'db',
]);

$select = \Elgg\Database\Select::fromTable('migrations', 'm');
$select->select('*');

$result = _elgg_services()->db->getData($select);

if (empty($result)) {
	echo elgg_echo('notfound');
	return;
}

$head = '<thead><tr>';
$head .= elgg_format_element('th', [], elgg_echo('admin:upgrades:db:name'));
$head .= elgg_format_element('th', [], elgg_echo('admin:upgrades:db:start_time'));
$head .= elgg_format_element('th', [], elgg_echo('admin:upgrades:db:end_time'));
$head .= elgg_format_element('th', [], elgg_echo('admin:upgrades:db:duration'));
$head .= '</tr></thead>';

$rows = '';
foreach ($result as $row) {
	$start = $row->start_time;
	$end = $row->end_time;
	
	$duration = date_diff(date_create($start), date_create($end))->format('%H:%I:%S');
	
	$rows .= "<tr><td>{$row->migration_name}</td><td>{$start}</td><td>{$end}</td><td>{$duration}</td></tr>";
}

$body = elgg_format_element('tbody', [], $rows);

echo elgg_format_element('table', ['class' => 'elgg-table'], $head . $body);
