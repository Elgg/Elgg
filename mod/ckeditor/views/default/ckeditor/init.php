<?php
/**
 * Initialize the CKEditor script
 */

?>
<script>
require(['elgg/ckeditor', 'jquery', 'jquery.ckeditor'], function(elggCKEditor, $) {
	$('.elgg-input-longtext').ckeditor(elggCKEditor.wordCount, elggCKEditor.config);
});
</script>
