<?php
/**
 * Elgg widget save action
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$guid = get_input('guid');
$params = $_REQUEST['params'];
$pageurl = get_input('pageurl');
$noforward = get_input('noforward',false);

$result = false;

if (!empty($guid)) {
	$result = save_widget_info($guid,$params);
}

if ($result) {
	system_message(elgg_echo('widgets:save:success'));
} else {
	register_error(elgg_echo('widgets:save:failure'));
}

if (!$noforward) {
	forward($_SERVER['HTTP_REFERER']);
}
