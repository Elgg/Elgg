<?php
/**
 * Elgg widget add action
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$guid = get_input('user');
$handler = get_input('handler');
$context = get_input('context');
$column = get_input('column');

$result = false;

if (!empty($guid)) {
	if ($user = get_entity($guid)) {
		if ($user->canEdit()) {
			$result = add_widget($user->getGUID(),$handler,$context,0,$column);
		}
	}
}

if ($result) {
	system_message(elgg_echo('widgets:save:success'));
} else {
	register_error(elgg_echo('widgets:save:failure'));
}

forward($_SERVER['HTTP_REFERER']);