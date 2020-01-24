<?php
/**
 * Plugin user settings
 *
 * Calls the plugin admin settings form body with type set to 'user'
 */

$vars['type'] = 'user';
$vars['user_guid'] = elgg_get_page_owner_guid();

// Can't use elgg_view_form() because it overrides the $vars['action'] parameter
echo elgg_view('forms/plugins/settings/save', $vars);
