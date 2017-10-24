<?php
$note = get_entity(get_input('guid'));
if (!$note || !$note->canEdit()) {
	return elgg_error_response();
}

$note->delete();

return elgg_ok_response();
