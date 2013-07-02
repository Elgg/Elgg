<?php
/**
 * Delete an upload
 */

$upload = get_entity(get_input('guid'));

if (elgg_instanceof($upload, 'object', 'ckeditor_upload') && $upload->delete()) {
	system_message(elgg_echo('ckeditor:success:delete'));
} else {
	register_error(elgg_echo('ckeditor:failure:delete'));
}

forward(REFERER);
