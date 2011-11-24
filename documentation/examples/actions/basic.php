<?php
/**
 * Demonstrates adding an annotation through an action
 *
 * This action adds a rating annotation to an entity. If this was coming from
 * a five-star rating tool, the rating would be a number between 0 and 5. The
 * GUID of the entity being rating is also submitted to the action.
 */

$rating = get_input('rating');
$guid = get_input('guid');

$entity = get_entity($guid);
if (!$entity) {
	register_error(elgg_echo('rating:failure'));
	forward(REFERER);
}

$entity->annotate('rating', $rating);

system_message(elgg_echo('rating:success'));
forward(REFERER);
