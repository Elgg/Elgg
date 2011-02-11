<?php
/**
 * Saves the available categories for the site
 *
 * @note The categories for an object are saved through an event handler: categories_save()
 *
 * @package ElggCategories
 */

$categories = get_input('categories');
$categories = string_to_tag_array($categories);

$site = elgg_get_site_entity();
$site->categories = $categories;
system_message(elgg_echo("categories:save:success"));

elgg_delete_admin_notice('categories_admin_notice_no_categories');

forward(REFERER);