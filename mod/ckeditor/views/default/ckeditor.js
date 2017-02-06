/**
 * BC wrapper for module ckeditor. Remove in Elgg 3.0
 *
 * @module ckeditor
 */
define(['ckeditor/ckeditor'], function (ckeditor) {
	if (window && window.console) {
		console.info("The 'ckeditor' module has been deprecated in favor of 'ckeditor/ckeditor' and will be removed in Elgg 3.0.");
	}

	return ckeditor;
});
