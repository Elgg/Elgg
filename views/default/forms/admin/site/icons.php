<?php

$site_icon = elgg_view('output/longtext', ['value' => elgg_echo('admin:site_icons:info')]);

$site_icon .= elgg_view('entity/edit/icon', [
	'entity' => elgg_get_site_entity(),
	'cropper_enabled' => true,
]);

echo elgg_view_module('info', elgg_echo('admin:site_icons:site_icon'), $site_icon);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
