<?php

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Exceptions\Http\UnauthorizedException;

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!$entity instanceof \ElggEntity || !$entity->hasCapability('likable')) {
	echo elgg_echo('error:missing_data');
	return;
}

if (!(bool) elgg_get_plugin_setting('details', 'likes') && !$entity->canEdit()) {
	throw new UnauthorizedException();
}

$list = elgg_list_annotations([
	'guid' => $guid,
	'annotation_name' => 'likes',
	'limit' => 99,
	'pagination' => false,
	'order_by' => new OrderByClause('a_table.time_created', 'desc'),
]);

echo elgg_format_element('div', ['class' => 'elgg-likes-popup'], $list);
