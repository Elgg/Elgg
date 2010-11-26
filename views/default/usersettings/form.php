<?php
/**
 * @package Elgg
 * @subpackage Core
 */

$form_body = "<div class=\"contentWrapper user_settings\">" . elgg_view("usersettings/user") . " ";
$form_body .= "<p>" . elgg_view('input/submit', array('value' => elgg_echo('save'))) . "</p></div>";

echo elgg_view('input/form', array('action' => "{$vars['url']}action/usersettings/save", 'body' => $form_body));