<?php

$site = elgg_get_site_entity();

$body = elgg_view_layout('maintenance', [
	'message' => $site->getPrivateSetting('elgg_maintenance_message') ?: elgg_echo('admin:maintenance_mode:default_message'),
	'filter' => false,
]);

echo elgg_view_page($site->getDisplayName(), $body, 'maintenance');
