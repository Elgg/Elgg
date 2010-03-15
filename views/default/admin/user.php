<?php
/**
 * Elgg administration user main screen
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Intro
echo "<div class='margin_top'>".elgg_view('output/longtext', array('value' => elgg_echo("admin:user:description")))."</div>";
//echo elgg_view("admin/user_opt/adduser");
// add a new user form
echo elgg_view('account/forms/useradd', array('show_admin'=>true));
// search for a user
echo elgg_view("admin/user_opt/search");

if ($vars['list']) {
	echo $vars['list'];
}