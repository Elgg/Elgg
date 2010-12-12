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

echo elgg_view('page/elements/html_begin', $vars);

echo '<div class="elgg-page elgg-classic">';
echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
echo elgg_view('page/elements/topbar', $vars);
echo elgg_view('page/elements/header', $vars);
echo elgg_view('page/elements/body', $vars);
echo elgg_view('page/elements/footer', $vars);
echo '</div>';

echo elgg_view('page/elements/html_end', $vars);
