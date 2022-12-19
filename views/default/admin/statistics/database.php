<?php
use Elgg\Database\Select;

// show database statistics

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

$header = '<tr><th>' . elgg_echo('admin:statistics:database:table') . '</th>';
$header .= '<th>' . elgg_echo('admin:statistics:database:row_count') . '</th></tr>';

$rows = '';
foreach ($tables as $table_name) {
	$qb = Select::fromTable($table_name);
	$qb->select('COUNT(*) AS total');

	$row_count = _elgg_services()->db->getDataRow($qb);
	$row_count = empty($row_count) ? 0 : (int) $row_count->total;

	$rows .= "<tr><td>{$table_name}</td><td>{$row_count}</td></tr>";
}

$body = "<table class='elgg-table'><thead>{$header}</thead><tbody>{$rows}</tbody></table>";

echo elgg_view_module('info', elgg_echo('admin:statistics:database'), $body);
