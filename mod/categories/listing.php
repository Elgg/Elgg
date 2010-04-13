<?php
/**
 * Elgg categories listing page
 *
 * @package ElggCategories
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);
$category = get_input("category");
$owner_guid = get_input("owner_guid", 0);
$subtype = get_input("subtype", ELGG_ENTITIES_ANY_VALUE);
$type = get_input("type", 'object');

$objects = list_entities_from_metadata('universal_categories',
										$category,
										$type,
										$subtype,
										$owner_guid,
										$limit,
										false,
										false,
										true,
										false);


$title = sprintf(elgg_echo('categories:results'), $category);

$content = elgg_view_title($title);
$content .= $objects;

$body = elgg_view_layout('two_column_left_sidebar', '', $content);

page_draw($title, $body);
