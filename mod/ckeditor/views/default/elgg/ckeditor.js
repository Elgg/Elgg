/**
 * BC wrapper for module elgg-ckeditor. Remove in Elgg 3.0
 *
 * @module elgg/ckeditor
 */
define(['elgg-ckeditor'], function (ckeditor) {
	if (window && window.console) {
		console.info("The 'elgg/ckeditor' module has been deprecated in favor of 'elgg-ckeditor' and will be removed in Elgg 3.0.");
	}

	return ckeditor;
});
