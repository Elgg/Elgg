<?php

/**
 * Delete a directory and all its contents
 *
 * @param string $directory Directory to delete
 *
 * @return bool
 *
 * @deprecated 3.1 Use elgg_delete_directory()
 */
function delete_directory($directory) {
	
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_delete_directory().', '3.1');
	
	if (!is_string($directory)) {
		return false;
	}
	
	return elgg_delete_directory($directory);
}

/**
 * Register a JavaScript file for inclusion
 *
 * This function handles adding JavaScript to a web page. If multiple
 * calls are made to register the same JavaScript file based on the $id
 * variable, only the last file is included. This allows a plugin to add
 * JavaScript from a view that may be called more than once. It also handles
 * more than one plugin adding the same JavaScript.
 *
 * jQuery plugins often have filenames such as jquery.rating.js. A best practice
 * is to base $name on the filename: "jquery.rating". It is recommended to not
 * use version numbers in the name.
 *
 * The JavaScript files can be local to the server or remote (such as
 * Google's CDN).
 *
 * @note Since 2.0, scripts with location "head" will also be output in the footer, but before
 *       those with location "footer".
 *
 * @param string $name     An identifier for the JavaScript library
 * @param string $url      URL of the JavaScript file
 * @param string $location Page location: head or footer. (default: head)
 * @param int    $priority Priority of the JS file (lower numbers load earlier)
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1 Use AMD modules and elgg_require_js()
 */
function elgg_register_js($name, $url, $location = 'head', $priority = null) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use AMD modules and elgg_require_js().', '3.1');
	
	return elgg_register_external_file('js', $name, $url, $location, $priority);
}

/**
 * Load a JavaScript resource on this page
 *
 * This must be called before elgg_view_page(). It can be called before the
 * script is registered. If you do not want a script loaded, unregister it.
 *
 * @param string $name Identifier of the JavaScript resource
 *
 * @return void
 * @since 1.8.0
 *
 * @deprecated 3.1 Use AMD modules and elgg_require_js()
 */
function elgg_load_js($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use AMD modules and elgg_require_js().', '3.1');
	
	elgg_load_external_file('js', $name);
}

/**
 * Unregister a JavaScript file
 *
 * @param string $name The identifier for the JavaScript library
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1
 */
function elgg_unregister_js($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', '3.1');
	
	return elgg_unregister_external_file('js', $name);
}

/**
 * Register a CSS file for inclusion in the HTML head
 *
 * @param string $name     An identifier for the CSS file
 * @param string $url      URL of the CSS file
 * @param int    $priority Priority of the CSS file (lower numbers load earlier)
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1 Use elgg_require_css()
 */
function elgg_register_css($name, $url, $priority = null) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_require_css().', '3.1');
	
	return elgg_register_external_file('css', $name, $url, 'head', $priority);
}

/**
 * Unregister a CSS file
 *
 * @param string $name The identifier for the CSS file
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1
 */
function elgg_unregister_css($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', '3.1');
	
	return elgg_unregister_external_file('css', $name);
}

/**
 * Load a CSS file for this page
 *
 * This must be called before elgg_view_page(). It can be called before the
 * CSS file is registered. If you do not want a CSS file loaded, unregister it.
 *
 * @param string $name Identifier of the CSS file
 *
 * @return void
 * @since 1.8.0
 *
 * @deprecated 3.1 Use elgg_require_css()
 */
function elgg_load_css($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_require_css().', '3.1');
	
	elgg_load_external_file('css', $name);
}
