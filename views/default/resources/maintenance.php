<?php

$site = elgg_get_site_entity();

$message = $site->elgg_maintenance_message ?: elgg_echo('admin:maintenance_mode:default_message');

$body = elgg_view_message('warning', $message);
$body .= elgg_view('core/account/login_box', ['title' => false]);

echo elgg_view_page(elgg_echo('admin:login'), ['content' => $body], 'maintenance');
