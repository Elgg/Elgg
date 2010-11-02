<?php
/**
 * Elgg categories plugin category saver
 *
 * @package ElggCategories
 */

$categories = get_input('categories');
$categories = string_to_tag_array($categories);

$site = $CONFIG->site;
$site->categories = $categories;
system_message(elgg_echo("categories:save:success"));

elgg_delete_admin_notice('categories_admin_notice_no_categories');

forward(REFERER);