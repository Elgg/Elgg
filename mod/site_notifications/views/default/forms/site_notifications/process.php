<?php

elgg_require_js('forms/site_notifications/process');

echo elgg_format_element('div', ['class' => 'site-notifications-container'], elgg_extract('list', $vars));

$footer = elgg_view('input/submit', [
	'value' => elgg_echo('delete'),
	'name' => 'delete',
	'class' => 'elgg-button-delete',
	'title' => elgg_echo('deleteconfirm:plural'),
	'data-confirm' => elgg_echo('deleteconfirm:plural')
]);

$footer .= elgg_view('input/button', [
	'value' => elgg_echo('site_notifications:toggle_all'),
	'class' => 'elgg-button elgg-button-cancel',
	'id' => 'site-notifications-toggle',
]);

elgg_set_form_footer($footer);
