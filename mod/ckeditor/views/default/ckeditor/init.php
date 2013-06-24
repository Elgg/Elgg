<?php
/**
 * Initialize the CKEditor script
 * 
 * Doing this inline enables the editor to initialize textareas loaded through ajax
 */

?>
<script>
require(['elgg/ckeditor', 'jquery', 'jquery.ckeditor'], function(elggCKEditor, $) {
	$('.elgg-input-longtext').ckeditor(elggCKEditor.wordCount, elggCKEditor.config);
});
</script>
