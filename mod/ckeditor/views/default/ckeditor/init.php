<?php
/**
 * Initialize the CKEditor script
 *
 * Doing this inline enables the editor to initialize textareas loaded through ajax
 */

// someone does not want the editor enabled for this field
if (!elgg_extract('editor', $vars, true)) {
	return;
}

$id = elgg_extract('id', $vars);
if (!$id) {
	return;
}

$editor_type = elgg_extract('editor_type', $vars); // eg simple

// editor_type
$config = 'elgg/ckeditor/config';
if ($editor_type && elgg_view_exists("elgg/ckeditor/config/{$editor_type}.js")) {
	$config = "elgg/ckeditor/config/{$editor_type}";
}

?>
<script>
	require(['elgg-ckeditor'], function (elggCKEditor) {
		elggCKEditor.bind('#<?php echo $id; ?>', '<?php echo $config; ?>');
	});
</script>
