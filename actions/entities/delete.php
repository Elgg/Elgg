<?php
/**
 * Default entity delete action
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid);

if (($entity) && ($entity->canEdit())) {
	if ($entity->delete()) {
		system_message(sprintf(elgg_echo('entity:delete:success'), $guid));
	} else {
		register_error(sprintf(elgg_echo('entity:delete:fail'), $guid));
	}
} else {
	register_error(sprintf(elgg_echo('entity:delete:fail'), $guid));
}

forward($_SERVER['HTTP_REFERER']);
