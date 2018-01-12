<?php
/**
 * Elgg JSON output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['body']
 */

elgg_set_http_header("Content-Type: application/json;charset=utf-8");

echo elgg_extract('body', $vars, '');
