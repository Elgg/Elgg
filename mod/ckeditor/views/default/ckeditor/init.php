<?php
/**
 * Initialize the CKEditor script
 * 
 * Doing this inline enables the editor to initialize textareas loaded through ajax
 */
if (!elgg_extract('editor', $vars, true)) {
	return;
}
?>
<script>
	require(['elgg/ckeditor/config', 'ckeditor/ckeditor', 'elgg/ckeditor'], function(config, CKEDITOR, elggCKEditor) {
		elggCKEditor.bind('.elgg-input-longtext');
	});
</script>
