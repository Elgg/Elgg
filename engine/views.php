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
		"jquery-migrate.js" => "vendor/bower-asset/jquery-migrate/jquery-migrate.min.js",
		"jquery-ui.js" => "vendor/bower-asset/jquery-ui/jquery-ui.min.js",

		"jquery.form.js" => "vendor/bower-asset/jquery-form/jquery.form.js",
		"jquery.colorbox.js" => "vendor/bower-asset/jquery-colorbox/jquery.colorbox-min.js",
		"require.js" => "vendor/bower-asset/requirejs/require.js",
		"text.js" => "vendor/bower-asset/text/text.js",
		
		// need to use some folder structure, because FontAwesome includes fonts relative to css
		"font-awesome/css/font-awesome.css" => "vendor/fortawesome/font-awesome/css/font-awesome.min.css",
		"font-awesome/fonts/" => "vendor/fortawesome/font-awesome/fonts",

		/**
		 * __DIR__ should be utilized when referring to assets that are checked in to version control.
		 */
		"/" => dirname(__DIR__) . "/_graphics",

		"elgg/ui.avatar_cropper.js" => dirname(__DIR__) . "/js/lib/ui.avatar_cropper.js",
		"elgg/ui.friends_picker.js" => dirname(__DIR__) . "/js/lib/ui.friends_picker.js",
		"elgg/ui.river.js" => dirname(__DIR__) . "/js/lib/ui.river.js",

		// For datepicker. More info in the jquery-ui.js view
		"jquery-ui/i18n/" => "vendor/bower-asset/jquery-ui/ui/minified/i18n",

		"jquery.imgareaselect.css" => dirname(__DIR__) . "/bower_components/jquery-imgareaselect/distfiles/css/imgareaselect-deprecated.css",
		"jquery.imgareaselect.js" => dirname(__DIR__) . "/bower_components/jquery-imgareaselect/jquery.imgareaselect.dev.js",
		"jquery.jeditable.js" => dirname(__DIR__) . "/bower_components/jquery-jeditable/jquery.jeditable.js",
		"jquery.ui.autocomplete.html.js" => dirname(__DIR__) . "/bower_components/jquery-ui-extensions/src/autocomplete/jquery.ui.autocomplete.html.js",
		"sprintf.js" => dirname(__DIR__) . "/bower_components/sprintf/src/sprintf.js",
	],
];
