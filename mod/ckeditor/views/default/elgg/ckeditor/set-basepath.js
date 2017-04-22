/**
 * This view exists for BC. Remove in Elgg 3.0.
 *
 * This view extends elgg.js and sets ckeditor basepath before jquery.ckeditor is loaded
 */
define('elgg/ckeditor/set-basepath', function (require) {
	if (window && window.console) {
		console.info("The 'elgg/ckeditor/set-basepath' module is unneeded and will be removed in Elgg 3.0");
	}

	var elgg = require('elgg');
	// This global variable must be set before the editor script loading.
	CKEDITOR_BASEPATH = elgg.get_simplecache_url('ckeditor/');
});
