<?php
return [
	// viewtype
	"default" => [
		// view => path
		
		/**
		 * Relative paths (no leading slash) are resolved relative to Elgg's install root.
		 *
		 * All assets managed by composer (not checked in to version control) should use this syntax.
		 */
		"jquery.js" => "vendor/bower-asset/jquery/dist/jquery.min.js",
		"jquery.min.map" => "vendor/bower-asset/jquery/dist/jquery.min.map",
		"jquery-ui.js" => "vendor/bower-asset/jquery-ui/jquery-ui.min.js",

		"jquery.form.js" => "vendor/bower-asset/jquery-form/jquery.form.js",
		"jquery.colorbox.js" => "vendor/bower-asset/jquery-colorbox/jquery.colorbox-min.js",
		"require.js" => "vendor/bower-asset/requirejs/require.js",
		"text.js" => "vendor/bower-asset/text/text.js",
		"sprintf.js" => "vendor/bower-asset/sprintf/src/sprintf.js",
		
		"jquery.imgareaselect.css" => "vendor/bower-asset/jquery.imgareaselect/distfiles/css/imgareaselect-deprecated.css",
		"jquery.imgareaselect.js" => "vendor/bower-asset/jquery.imgareaselect/jquery.imgareaselect.dev.js",

		"jquery-treeview/" => "vendor/bower-asset/jquery-treeview/",

		// need to use some folder structure, because FontAwesome includes fonts relative to css
		"font-awesome/" => "vendor/bower-asset/fontawesome/",

		// For datepicker. More info in the jquery-ui.js view
		"jquery-ui/i18n/" => "vendor/bower-asset/jquery-ui/ui/minified/i18n",

		/**
		 * __DIR__ should be utilized when referring to assets that are checked in to version control.
		 */

		"jquery.ui.autocomplete.html.js" => dirname(__DIR__) . "/bower_components/jquery-ui-extensions/src/autocomplete/jquery.ui.autocomplete.html.js",

		// CSS Reset
		"normalize.css" => "vendor/bower-asset/normalize-css/normalize.css",

		// Polyfills
		"weakmap-polyfill.js" => "vendor/npm-asset/weakmap-polyfill/weakmap-polyfill.min.js",
		"formdata-polyfill.js" => "vendor/npm-asset/formdata-polyfill/formdata.min.js",
	],
];
