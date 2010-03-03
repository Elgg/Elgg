<?php
/**
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$form_body = elgg_view("usersettings/user");
$form_body .= "<div class='divider'></div>".elgg_view('input/submit', array('value' => elgg_echo('save')));

echo elgg_view('input/form', array('action' => "{$vars['url']}action/usersettings/save", 'body' => $form_body));