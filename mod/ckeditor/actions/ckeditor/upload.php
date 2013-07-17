<?php
/**
 * Receive image from CKeditor
 */

$image_url = $msg = '';

if (count($_FILES) == 0 || !isset($_FILES['upload'])) {
	$msg = elgg_echo('ckeditor:failure:too_big');
} else {
	// @todo max size set to 700 pixels (pull out as plugin setting)
	$resizer = new CKEditorImageResizer(700);
	$service = new CKEditorUploadService(elgg_get_data_path(), elgg_get_logged_in_user_guid(), $resizer);
	$image_url = $service->store($_FILES['upload']);
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
