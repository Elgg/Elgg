<?php
/**
 * Receive image from CKeditor
 */

$msg = '';

$service = new CKEditorUploadService();
$image_url = $service->process($_FILES['upload'], elgg_get_logged_in_user_entity());
if (!$image_url) {
	$msg = $service->getErrorMessage();
}

echo elgg_view('ckeditor/upload_result', array(
	'callback' => get_input('CKEditorFuncNum'),
	'url' => $image_url,
	'msg' => $msg,
));
exit;
