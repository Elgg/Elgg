<?php
/**
 * @package Elgg
 * @subpackage Core
 */

$form_body = elgg_view("usersettings/user");
$form_body .= "<div class='divider'></div>".elgg_view('input/submit', array('value' => elgg_echo('save'), 'class' => 'submit_button usersettings_save'));

echo elgg_view('input/form', array('action' => elgg_get_site_url()."action/usersettings/save", 'body' => $form_body));