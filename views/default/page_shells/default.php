<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

// Set title
$site_title = elgg_get_config('sitename');
if (empty($vars['title'])) {
	$title = $site_title;
} else if (empty($site_title)) {
	$title = $vars['title'];
} else {
	$title = $site_title . ": " . $vars['title'];
}

echo elgg_view('page_elements/html_begin', $vars);

echo elgg_view('messages/list', array('object' => $vars['sysmessages']));
echo elgg_view('page_elements/topbar', $vars);
echo elgg_view('page_elements/header', $vars);
echo elgg_view('page_elements/content', $vars);
echo elgg_view('page_elements/footer', $vars);

echo elgg_view('page_elements/html_end', $vars);
