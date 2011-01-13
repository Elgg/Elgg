<?php
/**
 * Elgg Groups topic posts page
 * 
 * @package ElggGroups
 *
 * @deprecated 1.8
 */

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

elgg_load_library('elgg:topic');

$guid = get_input('topic');

register_error(elgg_echo('changebookmark'));

topic_handle_view_page($guid);
