<?php

/**
 * Elgg Groups edit a forum topic page
 * 
 * @package ElggGroups
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

//get the topic
$topic = get_entity((int) get_input('topic'));
$group = get_entity($topic->container_guid);
set_page_owner($group->guid);

group_gatekeeper();

$content = elgg_view("forms/forums/edittopic", array('entity' => $topic));
$body = elgg_view_layout('two_column_left_sidebar', '', $content);

page_draw(elgg_echo('groups:edittopic'), $body);

