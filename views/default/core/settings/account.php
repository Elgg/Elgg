<?php
/**
 * Account settings form wrapper
 * 
 * @package Elgg
 * @subpackage Core
 */

$form_body = elgg_view("forms/account/settings");
$form_body .= '<p class="bta">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));
$form_body .= '</p>';

echo elgg_view('input/form', array('action' => "action/usersettings/save", 'body' => $form_body));
