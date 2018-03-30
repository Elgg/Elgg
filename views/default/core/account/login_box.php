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

$title = elgg_extract('title', $vars, elgg_echo('login'));

$body = elgg_view_form('login', ['ajax' => true]);

echo elgg_view_module($module, $title, $body);
