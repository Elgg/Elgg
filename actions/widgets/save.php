<?php
/**
 * Elgg widget save action
 *
 * @package Elgg
 * @subpackage Core
 */

$guid = get_input('guid');
$params = get_input('params');
$pageurl = get_input('pageurl');
$noforward = get_input('noforward',false);

$result = false;

if (!empty($guid)) {
	$result = save_widget_info($guid,$params);
}

if ($noforward) {
	echo json_encode(array('result' => $result));
	exit;
}

if ($result) {
	system_message(elgg_echo('widgets:save:success'));
} else {
	register_error(elgg_echo('widgets:save:failure'));
}

forward($_SERVER['HTTP_REFERER']);
