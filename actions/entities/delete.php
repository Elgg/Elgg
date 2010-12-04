<?php
/**
 * Default entity delete action
 *
 * @package Elgg
 * @subpackage Core
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (($entity) && ($entity->canEdit())) {
	if ($entity->delete()) {
		system_message(elgg_echo('entity:delete:success', array($guid)));
	} else {
		register_error(elgg_echo('entity:delete:fail', array($guid)));
	}
} else {
	register_error(elgg_echo('entity:delete:fail', array($guid)));
}

forward(REFERER);
