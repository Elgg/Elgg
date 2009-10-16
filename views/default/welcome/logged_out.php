<?php
/**
 * Elgg sample welcome page (logged out)
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

//add various views to area1
$area1 = "<p>" . elgg_echo("welcome_message") . "</p>";
$area1 .= elgg_view("account/forms/login");

//draw to screen
echo $body;