<?php
/**
 * River item delete action
 *
 * @package Elgg
 * @subpackage Core
 */

$id = (int)get_input('id');

$items = elgg_get_river(['id' => $id]);
if (!$items) {
	register_error(elgg_echo('river:delete:fail'));
	forward(REFERER);
}

$item = $items[0];
if (!$item->canDelete()) {
	register_error(elgg_echo('river:delete:lack_permission'));
	forward(REFERER);
}

if ($item->delete()) {
	system_message(elgg_echo('river:delete:success'));
} else {
	register_error(elgg_echo('river:delete:fail'));
}

forward(REFERER);
