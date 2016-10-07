/**
 * This view extends elgg.js and sets ckeditor basepath before jquery.ckeditor is loaded
 */
define('elgg/ckeditor/set-basepath', function (require) {
	var elgg = require('elgg');
	// This global variable must be set before the editor script loading.
	CKEDITOR_BASEPATH = elgg.get_simplecache_url('ckeditor/');
});
