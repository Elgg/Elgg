<?php
/**
 * Initialize the CKEditor script
 * 
 * Doing this inline enables the editor to initialize textareas loaded through ajax
 */

?>
<script>
// This global variable must be set before the editor script loading.
CKEDITOR_BASEPATH = elgg.config.wwwroot + 'mod/ckeditor/vendors/ckeditor/';

require(['elgg/ckeditor', 'jquery', 'jquery.ckeditor'], function(elggCKEditor, $) {
	$('.elgg-input-longtext:not([data-cke-init])')
		.attr('data-cke-init', true)
		.ckeditor(elggCKEditor.init, elggCKEditor.config);
});
</script>
