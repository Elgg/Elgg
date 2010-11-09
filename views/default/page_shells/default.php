<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['config'] The site configuration settings, imported
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

// Set title
if (empty($vars['title'])) {
	$title = $vars['config']->sitename;
} else if (empty($vars['config']->sitename)) {
	$title = $vars['title'];
} else {
	$title = $vars['config']->sitename . ": " . $vars['title'];
}

echo elgg_view('page_elements/html_begin', $vars);
echo elgg_view('page_elements/topbar', $vars);
// @todo this probably should be somewhere else 
echo elgg_view('messages/list', array('object' => $vars['sysmessages']));
echo elgg_view('page_elements/header', $vars);
echo elgg_view('page_elements/content', $vars);
echo elgg_view('page_elements/footer', $vars);
echo elgg_view('page_elements/html_end', $vars);
