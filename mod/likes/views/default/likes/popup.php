<?php

use Elgg\Database\Clauses\OrderByClause;

$guid = get_input("guid");

if (!get_entity($guid)) {
	echo elgg_echo("error:missing_data");
	return;
}

$list = elgg_list_annotations([
	'guid' => $guid,
	'annotation_name' => 'likes',
	'limit' => 99,
	'pagination' => false,
	'order_by' => new OrderByClause('n_table.time_created', 'desc'),
]);

echo elgg_format_element('div', ['class' => 'elgg-likes-popup'], $list);
