<?php
/**
 * Receive image from CKeditor
 */

$image_url = $msg = '';

if (count($_FILES) == 0 || !isset($_FILES['upload'])) {
	$msg = elgg_echo('ckeditor:failure:too_big');
} else {
	$service = new CKEditorUploadService();
	$image_url = $service->store(elgg_get_logged_in_user_entity(), $_FILES['upload']);
	if (!$image_url) {
		$msg = $service->getErrorMessage();
	}
}

echo elgg_view('ckeditor/upload_result', array(
	'callback' => get_input('CKEditorFuncNum'),
	'url' => $image_url,
	'msg' => $msg,
));
exit;
