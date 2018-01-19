<?php

$site = elgg_get_site_entity();

$message = $site->getPrivateSetting('elgg_maintenance_message');

if (!$message) {
	$message = elgg_echo('admin:maintenance_mode:default_message');
}

elgg_load_css('maintenance');

elgg_set_http_header("HTTP/1.1 503 Service Unavailable");

$body = elgg_view_layout('maintenance', [
	'message' => $message,
	'site' => $site,
]);

echo elgg_view_page($site->getDisplayName(), $body, 'maintenance');
