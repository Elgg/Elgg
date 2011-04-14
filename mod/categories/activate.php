<?php
/**
 * Elgg categories plugin
 *
 * Activation file - runs when categories plugin is activated.
 *
 * @package ElggCategories
 */

/**
 * Add a reminder to set default categories.
 */
$site = elgg_get_site_entity();

if (!$site->categories) {
	$url = elgg_normalize_url('admin/plugin_settings/categories');
	$message = elgg_echo('categories:on_enable_reminder', array($url));
	elgg_add_admin_notice('categories_admin_notice_no_categories', $message);
}
