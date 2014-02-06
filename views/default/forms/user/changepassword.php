<?php
/**
 * Reset user password form
 */

$text = elgg_autop(elgg_echo('user:changepassword:change_password_confirm'));

$password1_label = elgg_echo('user:password:label');
$password1 = elgg_view('input/password', array(
	'name' => 'password1',
));

$password2_label = elgg_echo('user:password2:label');
$password2 = elgg_view('input/password', array(
	'name' => 'password2',
));

$u = elgg_view('input/hidden', array(
	'name' => 'u',
	'value' => $vars['guid'],
));

$c = elgg_view('input/hidden', array(
	'name' => 'c',
	'value' => $vars['code'],
));

$submit = elgg_view('input/submit', array(
	'value' => elgg_echo('changepassword')
));

echo <<<HTML
$text
<div><label>$password1_label</label>$password1</div>
<div><label>$password2_label</label>$password2</div>
<div class="elgg-foot">
$u $c
$submit
</div>
HTML;

