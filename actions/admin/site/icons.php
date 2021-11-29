<?php

$site = elgg_get_site_entity();

if (get_input('icon_remove')) {
	$site->deleteIcon();
	$site->deleteIcon('favicon');
} else {
	$site->saveIconFromUploadedFile('icon');
	$site->saveIconFromUploadedFile('icon', 'favicon');
}

return elgg_ok_response(elgg_echo('save:success'));
