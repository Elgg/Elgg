<?php

elgg_deprecated_notice("The action 'site_notifications/delete' is deprecated. Use 'entity/delete'.", '3.1');

$note = get_entity(get_input('guid'));
if (!$note || !$note->canEdit()) {
	return elgg_error_response();
}

$note->delete();

return elgg_ok_response();
