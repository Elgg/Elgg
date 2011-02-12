<?php
$form_body = elgg_view("forms/account/settings");

$form_body .= '<p class="bta">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));
$form_body .= '</p>';

echo $form_body;