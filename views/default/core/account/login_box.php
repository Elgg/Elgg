<?php
/**
 * Elgg login box
 *
 * @uses $vars['module'] The module name. Default: aside
 */

$module = elgg_extract('module', $vars, 'aside');

$title_text = elgg_get_session()->has('last_forward_from') ? elgg_echo('login:continue') : elgg_echo('login');

$title = elgg_extract('title', $vars, $title_text);

$body = elgg_view_form('login', ['ajax' => true]);

echo elgg_view_module($module, $title, $body);
