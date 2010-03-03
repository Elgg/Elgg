<?php

/**
 * Elgg groups: add post to a topic 
 *
 * @package ElggGroups
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

// Make sure we're logged in and have a CSRF token
gatekeeper();

// Get input
$topic_guid = (int) get_input('topic_guid');
$group_guid = (int) get_input('group_guid');
$post = get_input('topic_post');


// make sure we have text in the post
if (!$post) {
	register_error(elgg_echo("grouppost:nopost"));
	forward($_SERVER['HTTP_REFERER']);
}


// Check that user is a group member
$group = get_entity($group_guid);
$user = get_loggedin_user();
if (!$group->isMember($user)) {
	register_error(elgg_echo("groups:notmember"));
	forward($_SERVER['HTTP_REFERER']);
}


// Let's see if we can get an form topic with the specified GUID, and that it's a group forum topic
$topic = get_entity($topic_guid);
if (!$topic || $topic->getSubtype() != "groupforumtopic") {
	register_error(elgg_echo("grouptopic:notfound"));
	forward($_SERVER['HTTP_REFERER']);
}


// add the post to the forum topic
$post_id = $topic->annotate('group_topic_post', $post, $topic->access_id, $user->guid);
if ($post_id == false) {
	system_message(elgg_echo("groupspost:failure"));
	forward($_SERVER['HTTP_REFERER']);
}

// add to river
add_to_river('river/forum/create', 'create', $user->guid, $topic_guid, "", 0, $post_id);

system_message(elgg_echo("groupspost:success"));

forward($_SERVER['HTTP_REFERER']);
