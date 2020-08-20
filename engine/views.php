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
		"jquery.js" => "vendor/npm-asset/jquery/dist/jquery.min.js",
		"jquery.min.map" => "vendor/npm-asset/jquery/dist/jquery.min.map",
		"jquery-ui.js" => "vendor/npm-asset/components-jqueryui/jquery-ui.min.js",

		"jquery.form.js" => "vendor/npm-asset/jquery-form/jquery.form.js",
		"jquery.colorbox.js" => "vendor/npm-asset/jquery-colorbox/jquery.colorbox-min.js",
		"require.js" => "vendor/npm-asset/requirejs/require.js",
		"text.js" => "vendor/npm-asset/requirejs-text/text.js",
		"sprintf.js" => "vendor/npm-asset/sprintf-js/src/sprintf.js",
		
		// need to use some folder structure, because FontAwesome includes fonts relative to css
		"font-awesome/" => "vendor/fortawesome/font-awesome/",

		// For datepicker. More info in the jquery-ui.js view
		"jquery-ui/i18n/" => "vendor/npm-asset/components-jqueryui/ui/minified/i18n",

		/**
		 * __DIR__ should be utilized when referring to assets that are checked in to version control.
		 */

		"jquery.ui.autocomplete.html.js" => dirname(__DIR__) . "/bower_components/jquery-ui-extensions/src/autocomplete/jquery.ui.autocomplete.html.js",

		// CSS Reset
		"normalize.css" => "vendor/npm-asset/normalize.css/normalize.css",

		// Polyfills
		"weakmap-polyfill.js" => "vendor/npm-asset/weakmap-polyfill/weakmap-polyfill.min.js",
		"formdata-polyfill.js" => "vendor/npm-asset/formdata-polyfill/formdata.min.js",
		
		// CropperJS
		"cropperjs/" => "vendor/npm-asset/cropperjs/dist/",
		"jquery-cropper/" => "vendor/npm-asset/jquery-cropper/dist/",
	],
];
