<?php
/**
 * Admin area: edit default profile fields
 */

$add = elgg_view_form('profile/fields/add', array('class' => 'elgg-form-settings'), array());
$list = elgg_view('admin/appearance/profile_fields/list');

$reset = elgg_view('output/confirmlink', array(
	'text' => elgg_echo('reset'),
	'href' => 'action/profile/fields/reset',
	'title' => elgg_echo('profile:resetdefault'),
	'confirm' => elgg_echo('profile:resetdefault:confirm'),
	'class' => 'elgg-button elgg-button-cancel',
	'is_trusted' => 'true',
));

$body = <<<__HTML
$add
$list
<div class="mtl">$reset</div>
__HTML;

echo $body;
