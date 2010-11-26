<?php

/**
 * Elgg categories plugin category saver
 *
 * @package ElggCategories
 */

$categories = get_input('categories');
$categories = string_to_tag_array($categories);

global $CONFIG;
$site = $CONFIG->site;
$site->categories = $categories;
system_message(elgg_echo("categories:save:success"));

forward($_SERVER['HTTP_REFERER']);

