<?php
/**
 * Elgg categories plugin
 *
 * Deactivation file - runs when categories plugin is deactivated.
 *
 * @package ElggCategories
 */

/**
 * Clean up admin notices
 */
elgg_delete_admin_notice('categories_admin_notice_no_categories');
