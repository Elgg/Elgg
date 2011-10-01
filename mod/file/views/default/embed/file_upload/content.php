<?php
/**
 * Upload a file through the embed interface
 */

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form',
);
$upload_content = elgg_view_form('file/upload', $form_vars);

echo "<div class='embed-upload'>";
echo $upload_content;
echo "</div>";

// the tab we want to be forwarded to after upload is complete
echo elgg_view('input/hidden', array(
	'name' => 'embed_forward',
	'value' => 'file',
));