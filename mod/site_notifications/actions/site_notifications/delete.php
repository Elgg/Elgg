<?php
/**
 * 
 */

$note = get_entity(get_input('guid'));
if (!$note || !$note->canEdit()) {
	register_error(elgg_echo(''));
	forward(REFERER);
}

$note->delete();

forward(REFERER);
