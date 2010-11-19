<?php
/**
 * Elgg save widget settings action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$guid = get_input('guid');
$params = get_input('params');

$result = elgg_save_widget_settings($guid, $params);

if (!$result) {
	register_error(elgg_echo('widgets:save:failure'));
}

forward(REFERER);