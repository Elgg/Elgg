<?php
/**
 * Elgg administration site secret settings
 *
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;

$action = $CONFIG->wwwroot . 'action/admin/site/regenerate_secret';
$form_body = "<div class=\"contentWrapper\">";
$form_body .= elgg_view('forms/admin/site/regenerate_secret', array(
	'strength' => _elgg_get_site_secret_strength()
));
$form_body .= "</div>";

echo elgg_view('input/form', array(
	'action' => $action,
	'body' => $form_body
));
