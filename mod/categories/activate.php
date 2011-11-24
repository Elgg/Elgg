<?php
/**
 * Prompt the user to add categories after activating
 */

//categories_admin_notice_no_categories
$site = get_config('site');
if (!$site->categories) {
	$message = elgg_echo('categories:on_activate_reminder', array(elgg_normalize_url('admin/plugin_settings/categories')));
	elgg_add_admin_notice('categories_admin_notice_no_categories', $message);
}