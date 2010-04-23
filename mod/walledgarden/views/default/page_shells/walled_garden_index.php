<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
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
echo "<div id='walledgarden_sysmessages'>".elgg_view('messages/list', array('object' => $vars['sysmessages']))."</div>";
	
echo "<div id='walledgarden_container'><div id='walledgarden' class='clearfloat'>";				
	echo "<div class='walledgardenintro clearfloat'><h1>Welcome to:<br />" . $title . "</h1></div>";
	echo "<div class='walledgardenlogin clearfloat'>".$vars['body']."</div>";
echo "</div>";
echo "<div id='walledgarden_bottom'></div>";
echo "</div>";
echo elgg_view('page_elements/html_end', $vars);
?>

