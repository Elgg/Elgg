<?php
/**
 * Elgg login box
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['module'] The module name. Default: aside
 */

$module = elgg_extract('module', $vars, 'aside');
unset($vars['module']);

$title = elgg_extract('title', $vars, elgg_echo('login'));
unset($vars['title']);

$body = elgg_view_form('login', [
	'class' => 'card-block',
]);

$vars['class'] = elgg_extract_class($vars, 'elgg-login-box');
$vars['footer'] = elgg_view_menu('login', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-general elgg-menu-hz elgg-menu-page flex-column list-group list-group-flush',
		'item_class' => 'list-group-item',
	]);

echo elgg_view_module($module, $title, $body, $vars);
