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
	return elgg_error_response(elgg_echo('rating:failure'));
}

$entity->annotate('rating', $rating);

return elgg_ok_response('', elgg_echo('rating:success'));
