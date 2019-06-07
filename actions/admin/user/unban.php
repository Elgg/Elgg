<?php
/**
 * Unbans a user.
 */

$guid = (int) get_input('guid');

return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($guid) {
	$user = get_user($guid);
	
	if (!$user || !$user->canEdit()) {
		return elgg_error_response(elgg_echo('admin:user:unban:no'));
	}
	
	if (!$user->unban()) {
		return elgg_error_response(elgg_echo('admin:user:unban:no'));
	}
		
	return elgg_ok_response('', elgg_echo('admin:user:unban:yes'));
});
