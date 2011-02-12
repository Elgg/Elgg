<?php
$form_body = elgg_view("forms/account/settings");

$form_body .= '<div class="bta">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));
$form_body .= '</div>';

echo $form_body;