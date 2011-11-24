<?php
/**
 * Upload a file through the embed interface
 */

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form-embed',
);
echo elgg_view_form('file/upload', $form_vars);

// the tab we want to be forwarded to after upload is complete
echo elgg_view('input/hidden', array(
	'name' => 'embed_forward',
	'value' => 'file',
));