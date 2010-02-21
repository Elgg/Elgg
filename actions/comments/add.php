<?php
/**
 * Elgg add comment action
 *
 * @package Elgg
 * @author Curverider <curverider.co.uk>
 * @link http://elgg.org/
 */

// Make sure we're logged in; forward to the front page if not
gatekeeper();

// Get input
$entity_guid = (int) get_input('entity_guid');
$comment_text = get_input('generic_comment');

// make sure comment is not empty
if (empty($comment_text)) {
	register_error(elgg_echo("generic_comment:blank"));
	forward($_SERVER['HTTP_REFERER']);
}

// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("generic_comment:notfound"));
	forward($_SERVER['HTTP_REFERER']);
}

$user = get_loggedin_user();

$annotation = create_annotation($entity->guid, 
								'generic_comment',
								$comment_text, 
								"", 
								$user->guid, 
								$entity->access_id);

// tell user annotation posted
if (!$annotation) {
	register_error(elgg_echo("generic_comment:failure"));
	forward($_SERVER['HTTP_REFERER']);
}

// notify if poster wasn't owner
if ($entity->owner_guid != $user->guid) {
			
	notify_user($entity->owner_guid,
				$user->guid,
				elgg_echo('generic_comment:email:subject'),
				sprintf(
					elgg_echo('generic_comment:email:body'),
					$entity->title,
					$user->name,
					$comment_text,
					$entity->getURL(),
					$user->name,
					$user->getURL()
				)
			);
}

system_message(elgg_echo("generic_comment:posted"));
//add to river
add_to_river('annotation/annotate','comment',$user->guid,$entity->guid, "", 0, $annotation);

// Forward to the entity page
forward($entity->getURL());
