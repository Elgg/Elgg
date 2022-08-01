<?php

$site = elgg_get_site_entity();

$result = elgg_view_field([
	'#type' => 'text',
	'name' => 'sitename',
	'#label' => elgg_echo('installation:sitename'),
	'value' => $site->name,
]);

$result .= elgg_view_field([
	'#type' => 'text',
	'name' => 'sitedescription',
	'#label' => elgg_echo('installation:sitedescription'),
	'#help' => elgg_echo('installation:sitedescription:help'),
	'value' => $site->description,
]);

$result .= elgg_view_field([
	'#type' => 'email',
	'name' => 'siteemail',
	'#label' => elgg_echo('installation:siteemail'),
	'#help' => elgg_echo('installation:siteemail:help'),
	'value' => $site->email,
	'class' => 'elgg-input-text',
]);

echo elgg_view_module('info', elgg_echo('admin:settings:basic'), $result);
