<?php
/**
 * Show queue database statistics
 */

use Elgg\Database\Select;
use Elgg\Queue\DatabaseQueue;

$header = '<tr><th>' . elgg_echo('admin:statistics:queue:name') . '</th>';
$header .= '<th>' . elgg_echo('admin:statistics:queue:row_count') . '</th>';
$header .= '<th>' . elgg_echo('admin:statistics:queue:oldest') . '</th>';
$header .= '<th>' . elgg_echo('admin:statistics:queue:newest') . '</th></tr>';

$qb = Select::fromTable(DatabaseQueue::TABLE_NAME);
$qb->select('DISTINCT name');

$queue_names = _elgg_services()->db->getData($qb);
if (empty($queue_names)) {
	return;
}

$rows = '';
foreach ($queue_names as $queue) {
	$qb = Select::fromTable(DatabaseQueue::TABLE_NAME);
	$qb->select('COUNT(*) AS total');
	$qb->where($qb->compare('name', '=', $queue->name, ELGG_VALUE_STRING, true));

	$row_count = _elgg_services()->db->getDataRow($qb);
	$row_count = empty($row_count) ? 0 : elgg_number_format($row_count->total);
	
	$qb = Select::fromTable(DatabaseQueue::TABLE_NAME);
	$qb->select('MIN(timestamp) AS min');
	$qb->where($qb->compare('name', '=', $queue->name, ELGG_VALUE_STRING, true));
	
	$oldest = _elgg_services()->db->getDataRow($qb);
	$oldest = empty($oldest) ? 0 : (int) $oldest->min;
	$oldest = elgg_view('output/datetime-local', ['value' => $oldest]);
	
	$qb = Select::fromTable(DatabaseQueue::TABLE_NAME);
	$qb->select('MAX(timestamp) AS max');
	$qb->where($qb->compare('name', '=', $queue->name, ELGG_VALUE_STRING, true));
	
	$newest = _elgg_services()->db->getDataRow($qb);
	$newest = empty($newest) ? 0 : (int) $newest->max;
	$newest = elgg_view('output/datetime-local', ['value' => $newest]);
	
	$rows .= "<tr><td>{$queue->name}</td><td>{$row_count}</td><td>{$oldest}</td><td>{$newest}</td></tr>";
}

$body = "<table class='elgg-table'><thead>{$header}</thead><tbody>{$rows}</tbody></table>";

echo elgg_view_module('info', elgg_echo('admin:statistics:queue'), $body);
