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
		"jquery-ui.js" => "vendor/npm-asset/jquery-ui/dist/jquery-ui.min.js",

		"jquery.colorbox.js" => "vendor/npm-asset/jquery-colorbox/jquery.colorbox-min.js",
		"jquery.ui.touch-punch.js" => "vendor/npm-asset/jquery-ui-touch-punch/jquery.ui.touch-punch.js",
		"sprintf.js" => "vendor/npm-asset/sprintf-js/src/sprintf.js",
		
		// need to use some folder structure, because FontAwesome includes fonts relative to css
		"font-awesome/" => "vendor/fortawesome/font-awesome/",
		
		"jquery-ui/" => "vendor/npm-asset/jquery-ui/ui",
		"tagify/" => "vendor/npm-asset/yaireo--tagify/dist",
		
		// CSS Reset
		"normalize.css" => "vendor/npm-asset/normalize.css/normalize.css",
		
		// CropperJS
		"cropperjs/" => "vendor/npm-asset/cropperjs/dist/",
		"jquery-cropper/" => "vendor/npm-asset/jquery-cropper/dist/",
		
		/**
		 * __DIR__ should be utilized when referring to assets that are checked in to version control.
		 */
		"jquery.ui.autocomplete.html.js" => dirname(__DIR__) . "/bower_components/jquery-ui-extensions/src/autocomplete/jquery.ui.autocomplete.html.js",
	],
];
