<?php

/**
 * Elgg categories plugin category saver
 *
 * @package ElggCategories
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

$categories = get_input('categories');
$categories = string_to_tag_array($categories);

global $CONFIG;
$site = $CONFIG->site;
$site->categories = $categories;
system_message(elgg_echo("categories:save:success"));

forward($_SERVER['HTTP_REFERER']);

