<?php
return [
	// viewtype
	"default" => [
		// view => path
		
		/**
		 * Relative paths are resolved relative to Elgg's install root.
		 * 
		 * All assets managed by composer (not checked in to version control) should use this syntax.
		 */
		"js/jquery.js" => "vendor/bower-asset/jquery/dist/jquery.min.js",
		"js/jquery.min.map" => "vendor/bower-asset/jquery/dist/jquery.min.map",
		"js/jquery-migrate.js" => "vendor/bower-asset/jquery-migrate/jquery-migrate.min.js",
		"js/jquery.form.js" => "vendor/bower-asset/jquery-form/jquery.form.js",
		"js/jquery.imgareaselect.js" => "vendor/bower-asset/jquery-imgareaselect/jquery.imgareaselect.dev.js",
		"js/jquery.jeditable.js" => "vendor/bower-asset/jquery-jeditable/jquery.jeditable.js",
		"js/jquery.ui.autocomplete.html.js" => "vendor/bower-asset/jquery-ui-extensions/src/autocomplete/jquery.ui.autocomplete.html.js",
		"js/require.js" => "vendor/bower-asset/requirejs/require.js",
		"js/sprintf.js" => "vendor/bower-asset/sprintf/dist/sprintf.min.js",
		"js/text.js" => "vendor/bower-asset/text/text.js",
		"jquery.imgareaselect.css" => "vendor/bower-asset/jquery-imgareaselect/distfiles/css/imgareaselect-deprecated.css",

		/**
		 * __DIR__ should be utilized when referring to assets that are checked in to version control.
		 */
		"js/elgg/ui.autocomplete.js" => dirname(__DIR__) . "/js/lib/ui.autocomplete.js",
		"js/elgg/ui.avatar_cropper.js" => dirname(__DIR__) . "/js/lib/ui.avatar_cropper.js",
		"js/elgg/ui.friends_picker.js" => dirname(__DIR__) . "/js/lib/ui.friends_picker.js",
		"js/elgg/ui.river.js" => dirname(__DIR__) . "/js/lib/ui.river.js",

		"lightbox.css" => dirname(__DIR__) . "/vendors/elgg-colorbox-theme/colorbox.css",
		"colorbox-images/border1.png" => dirname(__DIR__) . "/vendors/elgg-colorbox-theme/colorbox-images/border1.png",
		"colorbox-images/border2.png" => dirname(__DIR__) . "/vendors/elgg-colorbox-theme/colorbox-images/border2.png",
		"colorbox-images/loading.gif" => dirname(__DIR__) . "/vendors/elgg-colorbox-theme/colorbox-images/loading.gif",
	],
];
