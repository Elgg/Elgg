<?php
/**
 * Elgg sample welcome page (logged in)
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

//add various views to area1
$area1 = "<h2>" . sprintf(elgg_echo("welcome:user"),$vars['user']->name) . "</h2>";
$area1 .= "<p>" . elgg_echo("welcome_message") . "</p><br />";
$area1 .= elgg_view('output/url', array(
	'href' => "{$vars['url']}action/logout", 
	'text' => elgg_echo('logout'),
	'is_action' => TRUE
));

//send area one to the appropriate canvas layout
$body = elgg_view_layout("one_column", $area1);

//draw to screen
echo $body;
