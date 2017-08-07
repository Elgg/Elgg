<?php
/**
 * River item delete action
 */

$id = (int) get_input('id');

$items = elgg_get_river(['id' => $id]);
if (!$items) {
	return elgg_error_response(elgg_echo('river:delete:fail'));
}

$item = $items[0];
if (!$item->canDelete()) {
	return elgg_error_response(elgg_echo('river:delete:lack_permission'));
}

if (!$item->delete()) {
	return elgg_error_response(elgg_echo('river:delete:fail'));
}

return elgg_ok_response('', elgg_echo('river:delete:success'));
