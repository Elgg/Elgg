<?php
/**
 * Maintenance mode layout
 *
 * @uses $vars['message'] Maintenance message
 */

$body = elgg_format_element('h1', [], elgg_get_site_entity()->getDisplayName());
$body .= elgg_view('output/longtext', ['value' => elgg_extract('message', $vars)]);
$body .= elgg_view('core/maintenance/login');

echo elgg_view_module('maintenance', '', $body);
