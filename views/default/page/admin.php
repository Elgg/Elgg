<?php
/**
 * Elgg pageshell for the admin area
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$body = elgg_view("page/elements/body/admin", $vars);
$head = elgg_view('page/elements/head', $vars);

echo elgg_view("page/shell", array("head" => $head, "body" => $body));
