<?php

$batch = elgg_get_admin_notices([
	'limit' => 0,
	'batch' => true,
]);

$batch->setIncrementOffset(false);

foreach ($batch as $notice) {
	$notice->delete();
}

return elgg_ok_response();
