<?php

$batch = elgg_get_admin_notices([
	'limit' => false,
	'batch' => true,
	'batch_inc_offset' => false,
]);

foreach ($batch as $notice) {
	$notice->delete();
}

return elgg_ok_response();
