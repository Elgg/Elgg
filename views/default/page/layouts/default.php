<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 */

if (!isset($vars["title"])) {
	$vars["title"] = false;
}

echo elgg_view("page/layouts/content/body", $vars);