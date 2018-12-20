<?php
/**
 * Reset an ElggUpgrade
 */

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!$entity instanceof ElggUpgrade) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

unset($entity->is_completed);
unset($entity->completed_time);
unset($entity->processed);
unset($entity->offset);

return elgg_ok_response('', elgg_echo('admin:action:upgrade:reset:success', [$entity->getDisplayName()]));
