<?php
/**
 * User account settings.
 *
 * Plugins should extend "forms/account/settings" to add to the settings.
 */

$form_body = elgg_view("forms/account/settings", $vars);

$form_body .= '<div class="elgg-foot">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));
$form_body .= '</div>';

echo $form_body;