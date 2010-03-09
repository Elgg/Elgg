<?php
/**
 * Elgg latest content page
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$offset = (int)get_input('offset', 0);

$title = elgg_view_title(elgg_echo('content:latest'));
set_context('search');
$content = elgg_list_registered_entities(array('limit' => 10, 'offset' => $offset, 'full_view' => FALSE, 'allowed_types' => array('object','group')));
set_context('latest');
$content = elgg_view_layout('two_column_left_sidebar', '', $title . $content);
page_draw(elgg_echo('content:latest'), $content);
