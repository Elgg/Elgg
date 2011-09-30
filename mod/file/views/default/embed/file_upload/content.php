<?php
/**
 * Upload a file through the embed interface
 */

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form',
);
$upload_content = elgg_view_form('file/upload', $form_vars);

echo "<div class='mbm'>" . elgg_echo('embed:upload_type') . "$input</div>";
echo "<div class='embed-upload'>";
echo $upload_content;
echo "</div>";