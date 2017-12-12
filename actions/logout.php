<?php
/**
 * Elgg logout action
 */

if (!logout()) {
	return elgg_error_response(elgg_echo('logouterror'));
}

return elgg_ok_response('', elgg_echo('logoutok'), '');
