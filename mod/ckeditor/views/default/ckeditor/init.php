<?php
/**
 * Initialize the CKEditor script
 * 
 * Doing this inline enables the editor to initialize textareas loaded through ajax
 */
?>
<script>
	require(['elgg/ckeditor'], function (elggCKEditor) {
		elggCKEditor.bind('.elgg-input-longtext');
	});
</script>
