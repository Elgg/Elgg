<?php
/**
 * Receive image from CKeditor
 */

$msg = '';
$image_url = 'http://static.php.net/www.php.net/images/php.gif';

echo elgg_view('ckeditor/upload_result', array(
	'callback' => get_input('CKEditorFuncNum'),
	'url' => $image_url,
	'msg' => $msg,
));
exit;
