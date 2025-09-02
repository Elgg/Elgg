<?php
/**
 * show database statistics
 */

use Elgg\Database\Select;

$tables = [
	'access_collections',
	'access_collection_membership',
	'annotations',
	'api_users',
	'config',
	'delayed_email_queue',
	'entities',
	'entity_relationships',
	'hmac_cache',
	'metadata',
	'queue',
	'river',
	'system_log',
	'users_apisessions',
	'users_remember_me_cookies',
	'users_sessions',
];

$header = [
	elgg_format_element('th', [], elgg_echo('admin:statistics:database:table')),
	elgg_format_element('th', [], elgg_echo('admin:statistics:database:row_count')),
];
$header = elgg_format_element('tr', [], implode(PHP_EOL, $header));
$header = elgg_format_element('thead', [], $header);

$rows = [];
foreach ($tables as $table_name) {
	$row = [];
	
	$qb = Select::fromTable($table_name);
	$qb->select('COUNT(*) AS total');

	$row_count = _elgg_services()->db->getDataRow($qb);
	$row_count = empty($row_count) ? 0 : (int) $row_count->total;
	
	$row[] = elgg_format_element('td', [], $table_name);
	$row[] = elgg_format_element('td', [], elgg_number_format($row_count));
	
	$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
}

$body = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

$table = elgg_format_element('table', ['class' => 'elgg-table'], $header . $body);

echo elgg_view_module('info', elgg_echo('admin:statistics:database'), $table);
